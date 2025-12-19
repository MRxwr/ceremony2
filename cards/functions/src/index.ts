import * as functions from "firebase-functions";
import * as admin from "firebase-admin";

admin.initializeApp();
const db = admin.firestore();

// 1. User Creation Trigger: Generates QR ID
export const onUserCreate = functions.auth.user().onCreate(async (user) => {
  const qrId = `QR-${user.uid.substring(0, 6).toUpperCase()}-${Date.now().toString(36).toUpperCase()}`;
  
  await db.collection("users").doc(user.uid).set({
    uid: user.uid,
    phone: user.phoneNumber || "",
    qr_id: qrId,
    role: "user", // Default role
    created_at: admin.firestore.FieldValue.serverTimestamp(),
  });
});

// 2. Transaction Processor (Merchant Action)
export const processTransaction = functions.https.onCall(async (data, context) => {
  // Security: Check if authenticated
  if (!context.auth) {
    throw new functions.https.HttpsError("unauthenticated", "User must be logged in.");
  }

  // Security: Check if merchant (In real app, check custom claims)
  // For this MVP, we'll check the user's role in Firestore
  const merchantSnap = await db.collection("users").doc(context.auth.uid).get();
  const merchantData = merchantSnap.data();
  
  if (!merchantData || merchantData.role !== "merchant") {
    throw new functions.https.HttpsError("permission-denied", "Only merchants can process transactions.");
  }

  const { qr_id, action, amount, store_id } = data;

  // Validate inputs
  if (!qr_id || !store_id || !action) {
    throw new functions.https.HttpsError("invalid-argument", "Missing required fields.");
  }

  // 1. Find User by QR ID
  const userQuery = await db.collection("users").where("qr_id", "==", qr_id).limit(1).get();
  if (userQuery.empty) {
    throw new functions.https.HttpsError("not-found", "User not found.");
  }
  const targetUser = userQuery.docs[0];
  const targetUid = targetUser.id;

  // 2. Get Store Rules
  const storeSnap = await db.collection("stores").doc(store_id).get();
  if (!storeSnap.exists) {
    throw new functions.https.HttpsError("not-found", "Store not found.");
  }
  const storeData = storeSnap.data();
  
  // Verify merchant owns this store (optional, depending on requirements)
  if (storeData?.owner_uid !== context.auth.uid) {
     throw new functions.https.HttpsError("permission-denied", "You do not own this store.");
  }

  // 3. Calculate Logic
  const loyaltyRef = db.collection("loyalty_data").doc(`${targetUid}_${store_id}`);
  const loyaltySnap = await loyaltyRef.get();
  
  let currentPoints = loyaltySnap.exists ? loyaltySnap.data()?.points || 0 : 0;
  let currentStamps = loyaltySnap.exists ? loyaltySnap.data()?.stamps || 0 : 0;

  let change = 0;

  if (action === "add") {
    // Add points/stamps based on logic
    if (storeData?.reward_logic.type === "stamps") {
      change = 1; // Default 1 stamp per visit
      currentStamps += change;
    } else {
      change = amount || 0; // Points based on spend
      currentPoints += change;
    }
  } else if (action === "redeem") {
    // Check balance
    const goal = storeData?.reward_logic.goal || 10;
    if (storeData?.reward_logic.type === "stamps") {
      if (currentStamps < goal) {
        throw new functions.https.HttpsError("failed-precondition", "Insufficient stamps.");
      }
      currentStamps -= goal;
      change = -goal;
    } else {
      if (currentPoints < goal) {
        throw new functions.https.HttpsError("failed-precondition", "Insufficient points.");
      }
      currentPoints -= goal;
      change = -goal;
    }
  }

  // 4. Atomic Update
  await loyaltyRef.set({
    uid: targetUid,
    store_id: store_id,
    points: currentPoints,
    stamps: currentStamps,
    last_updated: admin.firestore.FieldValue.serverTimestamp(),
  }, { merge: true });

  // 5. Audit Log
  await db.collection("audit_logs").add({
    action: action === "add" ? "add_points" : "redeem",
    actor_uid: context.auth.uid,
    target_uid: targetUid,
    store_id: store_id,
    details: {
      change: change,
      new_balance: storeData?.reward_logic.type === "stamps" ? currentStamps : currentPoints
    },
    timestamp: admin.firestore.FieldValue.serverTimestamp(),
  });

  return { success: true, new_balance: storeData?.reward_logic.type === "stamps" ? currentStamps : currentPoints };
});

// 3. Delete Account Service
export const deleteAccount = functions.https.onCall(async (data, context) => {
  if (!context.auth) {
    throw new functions.https.HttpsError("unauthenticated", "Must be logged in.");
  }

  const uid = context.auth.uid;

  // 1. Delete Loyalty Data
  const loyaltyQuery = await db.collection("loyalty_data").where("uid", "==", uid).get();
  const batch = db.batch();
  loyaltyQuery.docs.forEach((doc) => {
    batch.delete(doc.ref);
  });
  await batch.commit();

  // 2. Delete User Doc
  await db.collection("users").doc(uid).delete();

  // 3. Delete Auth User
  await admin.auth().deleteUser(uid);

  return { success: true };
});
