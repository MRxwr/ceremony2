import React from 'react';

export default function QRPage() {
  // In real app, fetch user.qr_id
  const qrValue = "QR-USER-123456"; 

  return (
    <div className="flex flex-col items-center justify-center h-[80vh] space-y-8">
      <div className="text-center">
        <h2 className="text-2xl font-bold text-gray-800">Scan to Earn</h2>
        <p className="text-gray-500">Show this code to the merchant</p>
      </div>
      
      <div className="bg-white p-8 rounded-3xl shadow-xl border-2 border-indigo-100">
        {/* Placeholder for QR Code Component */}
        <div className="w-64 h-64 bg-gray-900 flex items-center justify-center text-white">
          [QR Code: {qrValue}]
        </div>
      </div>
      
      <p className="text-xs text-gray-400 font-mono">{qrValue}</p>
    </div>
  );
}
