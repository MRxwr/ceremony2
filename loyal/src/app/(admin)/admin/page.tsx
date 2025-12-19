import React from 'react';

export default function AdminDashboard() {
  return (
    <div className="space-y-8">
      <h2 className="text-3xl font-bold text-gray-800">System Overview</h2>
      
      <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div className="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
          <h3 className="text-gray-500 text-sm font-medium uppercase">Total Users</h3>
          <p className="text-3xl font-bold text-gray-900 mt-2">1,245</p>
          <span className="text-green-500 text-sm font-medium">+12% this week</span>
        </div>
        <div className="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
          <h3 className="text-gray-500 text-sm font-medium uppercase">Active Stores</h3>
          <p className="text-3xl font-bold text-gray-900 mt-2">48</p>
          <span className="text-gray-400 text-sm font-medium">3 pending approval</span>
        </div>
        <div className="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
          <h3 className="text-gray-500 text-sm font-medium uppercase">Transactions Today</h3>
          <p className="text-3xl font-bold text-gray-900 mt-2">856</p>
        </div>
      </div>

      <div className="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div className="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
          <h3 className="font-semibold text-gray-800">Store Approval Queue</h3>
          <button className="text-indigo-600 text-sm font-medium hover:underline">View All</button>
        </div>
        <table className="w-full text-left text-sm text-gray-600">
          <thead className="bg-gray-50 text-gray-500 font-medium border-b border-gray-200">
            <tr>
              <th className="px-6 py-3">Store Name</th>
              <th className="px-6 py-3">Owner</th>
              <th className="px-6 py-3">Date</th>
              <th className="px-6 py-3">Action</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-gray-200">
            <tr>
              <td className="px-6 py-4 font-medium text-gray-900">Urban Beans</td>
              <td className="px-6 py-4">john.doe@example.com</td>
              <td className="px-6 py-4">Dec 19, 2025</td>
              <td className="px-6 py-4 space-x-2">
                <button className="text-green-600 hover:text-green-800 font-medium">Approve</button>
                <button className="text-red-600 hover:text-red-800 font-medium">Reject</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  );
}
