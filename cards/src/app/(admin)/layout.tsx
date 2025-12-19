import React from 'react';

export default function AdminLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  return (
    <div className="min-h-screen bg-gray-100 flex">
      <aside className="w-64 bg-gray-900 text-white hidden md:block">
        <div className="p-6">
          <h1 className="text-2xl font-bold">Super Admin</h1>
        </div>
        <nav className="mt-6">
          <a href="/admin" className="block px-6 py-3 bg-gray-800 border-l-4 border-indigo-500">Dashboard</a>
          <a href="/admin/stores" className="block px-6 py-3 hover:bg-gray-800 text-gray-400 hover:text-white">Stores</a>
          <a href="/admin/users" className="block px-6 py-3 hover:bg-gray-800 text-gray-400 hover:text-white">Users</a>
          <a href="/admin/cms" className="block px-6 py-3 hover:bg-gray-800 text-gray-400 hover:text-white">CMS</a>
        </nav>
      </aside>
      <main className="flex-1 p-8 overflow-y-auto">
        {children}
      </main>
    </div>
  );
}
