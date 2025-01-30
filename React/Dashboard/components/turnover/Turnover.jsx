import React, { useState, useEffect } from 'react';
import GraphTurnover from './GraphTurnover.jsx';

const Turnover = () => {
    const currentYear = new Date().getFullYear();
    const [year, setYear] = useState(currentYear);
    const [turnoverData, setTurnoverData] = useState([]);

    useEffect(() => {
        const fetchTurnover = async () => {
            try {
                const response = await fetch(`/api/dashboard/turnoverYear?q=${year}`);
                if (!response.ok) {
                    throw new Error("Une erreur est survenue pendant la récupération des données du Chiffre d'Affaire.");
                }
                const data = await response.json();
                setTurnoverData(data);
            } catch (err) {
                console.error("Une erreur API est survenue :", err);
                setTurnoverData([]);
            }
        };

        fetchTurnover();
    }, [year]);

    const handleYearChange = (e) => {
        setYear(parseInt(e.target.value, 10));
    };

    const years = Array.from({ length: 16 }, (_, i) => currentYear - 10 + i);

    return (
        <>
            <select value={year} onChange={handleYearChange}>
                {years.map((yr) => (
                    <option key={yr} value={yr}>
                        {yr}
                    </option>
                ))}
            </select>
            <GraphTurnover year={year} data={turnoverData} />
        </>
    );
}

export default Turnover;
