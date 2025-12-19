import React from 'react';

export default function MerchantLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  return (
    <div className="min-h-screen bg-slate-100">
      <header className="bg-slate-900 text-white p-4 shadow-md flex justify-between items-center">
        <h1 className="text-lg font-bold">Merchant Portal</h1>
        <div className="text-sm bg-slate-800 px-3 py-1 rounded-full">Store: Coffee House</div>
      </header>
      <main className="p-4 max-w-md mx-auto">
        {children}
      </main>
    </div>
  );
}
