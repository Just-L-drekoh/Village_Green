import React, { useState, useEffect } from 'react';
import GraphTurnover from './GraphTurnover.jsx';

const Turnover = () => {
    const currentYear = new Date().getFullYear();
    const [year, setYear] = useState(currentYear);
    const [turnoverData, setTurnoverData] = useState([]);

    useEffect(() => {
        const fetchTurnover = async () => {
            try {
                const response = await fetch(`/api/dashboard/turnover?q=${year}`);
                if (!response.ok) {
                    throw new Error("Une erreur est survenue pendant la récuperation de données de Chiffre d'Affaire .");
                }
                const data = await response.json();
                setTurnoverData(data);
            } catch (err) {
                console.error("<une erreur D'API est survenue", err);
                setTurnoverData([]);
            }
        };

        fetchTurnover();
    }, [year]);



    return (
        <>

            <GraphTurnover year={year} data={turnoverData} />
        </>
    );
}

export default Turnover;