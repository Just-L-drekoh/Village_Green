import React from 'react';

const OrderList = ({ orders }) => {
    return (
        <div className="space-y-4">
            <ul className="space-y-4 w-full max-w-2x">
                {orders.map((order) => (
                    <li
                        key={order.ref}
                        className="flex flex-col md:flex-row items-center justify-between p-4 border border-gray-300 rounded-xl shadow-md hover:shadow-lg hover:scale-105 transition-transform duration-200 bg-white mx-auto"
                    >
                        <div className="flex flex-col items-center md:items-start text-center md:text-left">
                            <h3 className="text-lg font-semibold text-gray-800">
                                {order.total} €
                            </h3>
                            <p className="text-sm text-gray-500">{order.paymentDate}</p>
                        </div>

                        <div className="mt-2 md:mt-0 text-center">
                            <span
                                className={`px-3 py-1 rounded-full text-xs font-semibold ${
                                    order.paymentStatus === 'paiement accepté'
                                        ? 'bg-green-100 text-green-700'
                                        : 'bg-red-100 text-red-700'
                                }`}
                            >
                                {order.paymentStatus}
                            </span>
                            <p className="text-sm text-gray-500 mt-1">{order.date}</p>
                        </div>
                    </li>
                ))}
            </ul>
        </div>
    );
};

export default OrderList;
