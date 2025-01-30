import React, { useState, useEffect } from 'react';

const TurnoverSupplier = () => {
    const [supplier, setSupplier] = useState('');
    const [turnoverSupplier, setTurnoverSupplier] = useState([]);
    const [error, setError] = useState(false);
    const [loading, setLoading] = useState(false);

    useEffect(() => {

        const fetchTurnoverSupplier = async () => {
            setLoading(true);
            setError(false);
            try {
                const response = await fetch(`api/dashboard/turnoverSupplier?q=${supplier}`);
                if (!response.ok) {
                    throw new Error("Une erreur est survenue pendant la récupération du chiffre d'affaire du fournisseur");
                }
                const data = await response.json();
                console.log('Données reçues:', data); // Correction ici pour voir les vraies données
                setTurnoverSupplier(data);
            } catch (err) {
                console.error("Une erreur API est survenue :", err);
                setError(true);
                setTurnoverSupplier([]);
            } finally {
                setLoading(false);
            }
        };

        fetchTurnoverSupplier();
    }, [supplier]); 


    return <>
    <input
                    type="text"
                    placeholder="Rechercher un fournisseur..."
                    value={supplier}
                    onChange={(e) => setSupplier(e.target.value)}
                />    </>
    
};



export default TurnoverSupplier;
