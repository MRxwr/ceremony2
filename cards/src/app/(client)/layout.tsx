import React from 'react';
import Link from 'next/link';

export default function ClientLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  return (
    <div className="min-h-screen bg-gray-50 pb-20">
      <header className="bg-white shadow-sm p-4 sticky top-0 z-10">
        <h1 className="text-xl font-bold text-indigo-600">My Wallet</h1>
      </header>
      <main className="p-4">
        {children}
      </main>
      <nav className="fixed bottom-0 w-full bg-white border-t border-gray-200 flex justify-around p-4">
        <Link href="/" className="flex flex-col items-center text-gray-600 hover:text-indigo-600">
          <span className="text-xs">Wallet</span>
        </Link>
        <Link href="/qr" className="flex flex-col items-center text-gray-600 hover:text-indigo-600">
          <span className="text-xs">My QR</span>
        </Link>
        <Link href="/discover" className="flex flex-col items-center text-gray-600 hover:text-indigo-600">
          <span className="text-xs">Discover</span>
        </Link>
        <Link href="/profile" className="flex flex-col items-center text-gray-600 hover:text-indigo-600">
          <span className="text-xs">Profile</span>
        </Link>
      </nav>
    </div>
  );
}
