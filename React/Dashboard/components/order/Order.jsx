import React from "react";
import { useState , useEffect , useCallback} from 'react';
import SearchBar from "../SearchBar";
import OrderList from "./OrderList";
import ErrorDisplay from "../ErrorDisplay";

const Order = () => {
    const [query, setQuery] = useState("");
    const [orders, setOrders] = useState([]);
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState("");

    const fetchOrders = useCallback(async()=>{
        if(query.trim() === ""){
            setOrders([]);
            setError("");
            return;
        }

        setLoading(true);
        setError("");

        try{
            const response = await fetch(
                `/api/dashboard/orders?q=${encodeURIComponent(query)}`
            )
            if (!response.ok) {
                throw new Error (
                    "Une Erreur est survevue lors du chargement des Commandes."
                );
            }
            const data = await response.json()
            setOrders(data);

        }catch(err){
            console.error("Une Ereur Api est survenue:", err);
            setError("Impossible de charger les Commandes. Veuillez reessayer.");
            setOrders([]);
        } finally {
            setLoading(false);
        }
    }, [query])


    useEffect(()=>{
        const timeout = setTimeout (()=>{
            fetchOrders();
        }, 300);

        return(()=> clearTimeout(timeout));

    }, [query, fetchOrders]);


    return (
        <>
        <SearchBar query={query} setQuery={setQuery} placeholder="Rechercher une Commande par la Reference ..." title="Rechercher une Commande" />

        <div className="container mx-auro p-6">
            {loading && (
                <div className="text-center text-gray-500">
                    <p>Chargement de la Commande</p>
                </div>
            )}

            {error && <ErrorDisplay message={error} />}

            {orders.length > 0 && <OrderList orders={orders} />}
        </div>
        </>
    );
    };

export default Order;