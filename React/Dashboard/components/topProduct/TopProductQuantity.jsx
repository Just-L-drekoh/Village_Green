import React, { useState, useEffect } from "react";

const TopProductQuantity = () => {
    const [topProductQuantity, setTopProductQuantity] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [year, setYear] = useState(new Date().getFullYear());

    useEffect(() => {
        const fetchTopProductQuantity = async () => {
            setLoading(true);
            try {
                const response = await fetch(`/api/dashboard/topProductQuantityOrder?q=${year}`);
                if (!response.ok) {
                    throw new Error("Une erreur est survenue pendant la récupération des données du Produit le plus vendu.(Quantité)");
                }
                const data = await response.json();
                setTopProductQuantity(data.data || []); 
                console.log(data)
                setError(null);
            } catch (err) {
                console.error("Une erreur API est survenue :", err);
                setError(err.message);
                setTopProductQuantity([]);
            } finally {
                setLoading(false);
            }
        };

        fetchTopProductQuantity();
    }, [year]);


    return (
        <div className="max-w-xl mx-auto p-6 bg-white rounded-lg shadow-md">
            <h2 className="text-2xl font-semibold text-gray-800 mb-4">
                📊 Top Produit Commandés ({year})
            </h2>

            <div className="mb-4">
                <label className="block text-sm font-medium text-gray-700 mb-1">
                    Sélectionnez une année :
                </label>
                <input
                    type="number"
                    value={year}
                    onChange={(e) => setYear(e.target.value)}
                    min="2000"
                    max={new Date().getFullYear()}
                    className="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
                />
            </div>

            {loading && <p className="text-blue-500 text-center font-medium">⏳ Chargement...</p>}
            {error && <p className="text-red-500 text-center font-medium">{error}</p>}

            {!loading && !error && (
                <div className="mt-4">
                    <p className="text-lg font-medium text-gray-700 mb-2">
                        📌 Les produits les plus commandés (Top 10):
                    </p>
                    {topProductQuantity.length > 0 ? (
                        <ul className="space-y-2">
                            {topProductQuantity.map((product, index) => (
                                <li
                                    key={product.ref || index}
                                    className="bg-gray-100 p-3 rounded-lg shadow-sm flex justify-between items-center"
                                >
                                    <div>
                                        <strong className="text-gray-800">{product.label}</strong>
                                        <p className="text-gray-600 text-sm">
                                            Quantité : {product.total_quantity} | Fournisseur: {product.supplier}
                                        </p>
                                    </div>
                                </li>
                            ))}
                        </ul>
                    ) : (
                        <p className="text-gray-500 text-center">Aucun produit trouvé pour cette année.</p>
                    )}
                </div>
            )}
        </div>
    );
};

export default TopProductQuantity;
