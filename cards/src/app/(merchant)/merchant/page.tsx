'use client';
import React, { useState } from 'react';

export default function MerchantScannerPage() {
  const [scannedData, setScannedData] = useState<string | null>(null);
  const [processing, setProcessing] = useState(false);

  const handleScan = (data: string) => {
    if (data) {
      setScannedData(data);
      // Open Action Modal
    }
  };

  const handleTransaction = async (action: 'add' | 'redeem') => {
    setProcessing(true);
    try {
      // Call Cloud Function: processTransaction({ qr_id: scannedData, action, ... })
      console.log(`Processing ${action} for ${scannedData}`);
      alert("Transaction Successful!");
      setScannedData(null);
    } catch (error) {
      alert("Error processing transaction");
    } finally {
      setProcessing(false);
    }
  };

  return (
    <div className="space-y-6">
      {!scannedData ? (
        <div className="bg-white rounded-xl shadow-md overflow-hidden">
          <div className="bg-slate-800 text-white p-4 text-center">
            <h2 className="font-semibold">Scan Customer QR</h2>
          </div>
          <div className="h-80 bg-black flex items-center justify-center text-gray-400">
            {/* Integrate html5-qrcode here */}
            [Camera Viewfinder]
            <button onClick={() => handleScan("QR-MOCK-USER")} className="absolute bottom-20 bg-white text-black px-4 py-2 rounded">
              Simulate Scan
            </button>
          </div>
        </div>
      ) : (
        <div className="bg-white rounded-xl shadow-lg p-6 space-y-6 animate-in fade-in slide-in-from-bottom-4">
          <div className="text-center">
            <div className="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg className="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <h3 className="text-xl font-bold text-gray-900">Customer Identified</h3>
            <p className="text-sm text-gray-500 font-mono mt-1">{scannedData}</p>
          </div>

          <div className="grid grid-cols-2 gap-4">
            <button 
              onClick={() => handleTransaction('add')}
              disabled={processing}
              className="bg-indigo-600 hover:bg-indigo-700 text-white py-4 rounded-xl font-semibold shadow-sm transition-colors"
            >
              Add Stamp
            </button>
            <button 
              onClick={() => handleTransaction('redeem')}
              disabled={processing}
              className="bg-white border-2 border-indigo-600 text-indigo-600 hover:bg-indigo-50 py-4 rounded-xl font-semibold transition-colors"
            >
              Redeem Reward
            </button>
          </div>
          
          <button onClick={() => setScannedData(null)} className="w-full text-gray-400 text-sm hover:text-gray-600">
            Cancel
          </button>
        </div>
      )}
    </div>
  );
}
