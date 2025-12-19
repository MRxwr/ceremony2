import React from 'react';

export default function WalletPage() {
  // In a real app, fetch from Firestore: collection('loyalty_data').where('uid', '==', user.uid)
  const mockCards = [
    { id: '1', storeName: 'Coffee House', points: 7, goal: 10, color: 'bg-amber-700' },
    { id: '2', storeName: 'Burger Joint', points: 450, goal: 1000, color: 'bg-red-600' },
  ];

  return (
    <div className="space-y-6">
      <h2 className="text-lg font-semibold text-gray-800">Your Cards</h2>
      <div className="flex overflow-x-auto space-x-4 pb-4 snap-x">
        {mockCards.map((card) => (
          <div key={card.id} className={`snap-center shrink-0 w-80 h-48 rounded-xl shadow-lg p-6 text-white flex flex-col justify-between ${card.color}`}>
            <div className="flex justify-between items-start">
              <h3 className="text-2xl font-bold">{card.storeName}</h3>
              <div className="w-10 h-10 bg-white/20 rounded-full"></div>
            </div>
            <div>
              <div className="flex justify-between text-sm mb-2">
                <span>Progress</span>
                <span>{card.points} / {card.goal}</span>
              </div>
              <div className="w-full bg-black/20 rounded-full h-2">
                <div 
                  className="bg-white h-2 rounded-full transition-all duration-500" 
                  style={{ width: `${(card.points / card.goal) * 100}%` }}
                ></div>
              </div>
            </div>
          </div>
        ))}
      </div>
      
      <div className="bg-white rounded-xl p-4 shadow-sm">
        <h3 className="font-semibold mb-2">Latest News</h3>
        <div className="text-sm text-gray-600">
          <p>Double points weekend at Coffee House!</p>
          <span className="text-xs text-gray-400">2 hours ago</span>
        </div>
      </div>
    </div>
  );
}
