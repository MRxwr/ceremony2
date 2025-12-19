import { initializeApp, getApps, getApp } from "firebase/app";
import { getAuth } from "firebase/auth";
import { getFirestore } from "firebase/firestore";
import { getStorage } from "firebase/storage";
import { getFunctions } from "firebase/functions";
import { getAnalytics, isSupported } from "firebase/analytics";

const firebaseConfig = {
  apiKey: "AIzaSyBVI-FDdGETGPji0x9jGBy_43zEvfH70dE",
  authDomain: "loyalcard-d0685.firebaseapp.com",
  projectId: "loyalcard-d0685",
  storageBucket: "loyalcard-d0685.firebasestorage.app",
  messagingSenderId: "417991780442",
  appId: "1:417991780442:web:2e464d63c45ef31fd91063",
  measurementId: "G-KV9ZRH96SS"
};

// Initialize Firebase
const app = !getApps().length ? initializeApp(firebaseConfig) : getApp();

export const auth = getAuth(app);
export const db = getFirestore(app);
export const storage = getStorage(app);
export const functions = getFunctions(app);

// Initialize Analytics only on client side
let analytics: any = null;
if (typeof window !== "undefined") {
  isSupported().then((supported) => {
    if (supported) {
      analytics = getAnalytics(app);
    }
  });
}

export { analytics };
