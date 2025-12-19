import { Timestamp } from 'firebase/firestore';

export type UserRole = 'user' | 'merchant' | 'admin';

export interface UserProfile {
  uid: string;
  phone: string;
  name?: string;
  qr_id: string;
  role: UserRole;
  created_at: Timestamp;
}

export interface Store {
  store_id: string;
  owner_uid: string;
  name: string;
  logo_url: string;
  brand_color: string;
  status: 'pending' | 'active' | 'rejected';
  reward_logic: {
    type: 'stamps' | 'points';
    goal: number;
    value_per_unit?: number; // e.g., 1 stamp per $10 spent, or 1 stamp per visit
  };
  location?: {
    lat: number;
    lng: number;
    address?: string;
  };
}

export interface LoyaltyCard {
  id: string; // Composite: `${uid}_${store_id}`
  uid: string;
  store_id: string;
  points: number;
  stamps: number;
  last_updated: Timestamp;
}

export interface SystemContent {
  id: string;
  type: 'terms' | 'privacy' | 'news';
  title: string;
  content: string;
  target_store_id?: string; // If null, global
  published_at: Timestamp;
}

export interface AuditLog {
  id: string;
  action: 'add_points' | 'redeem' | 'create_store' | 'update_store' | 'delete_account';
  actor_uid: string;
  target_uid?: string;
  store_id?: string;
  details: any;
  timestamp: Timestamp;
}
