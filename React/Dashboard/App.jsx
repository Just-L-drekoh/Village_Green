import React from 'react';
import { useState, useEffect, useCallback } from 'react';
import SearchBar from './SearchBar.jsx'
import UserList from './UserList.jsx'
import ErrorDisplay from './ErrorDisplay.jsx';
const App = () => {
    const [query, setQuery] =useState("");
    const [users, setUsers]  = useState([]);
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState("");

    const fetchUsers = useCallback(async ()=> {
        if (query.trim() === "") {
            setUsers([]);
            setError("");
            return;
        }

        setLoading(true);
        setError("");

        try {
            const response = await fetch(
                `/api/dashboard?q=${encodeURIComponent(query)}`
            );
            if (!response.ok) {
                throw new Error(
                    "Une erreur est survenue lors du chargement des Utilisateurs."
                );
            }
            const data = await response.json();
            setUsers(data);
            console.log(users)
        } catch (err) {
            console.error("API Error:", err);
            setError("Impossible de charger les Utilisateurs. Veuillez réessayer.");
            setUsers([]);
        } finally {
            setLoading(false);
        }
    }, [query]);

    useEffect(()=> {
        const timeout = setTimeout(()=> {
            fetchUsers();
        }, 300);

        return () => clearTimeout(timeout);
    }, [query, fetchUsers]);
    
    return (
        <>
          <SearchBar query={query} setQuery={setQuery} />
    
          <div className="container mx-auto p-6">
            {loading && (
              <div className="text-center text-gray-500">
                <p>Chargement de l'utilisateur</p>
              </div>
            )}
    
            {error && <ErrorDisplay error={error} />}
    
            {!loading && !error && users.length === 0 && query.trim() !== "" && (
              <div className="text-center text-gray-500">
                <p>Aucun Utilisateur</p>
              </div>
            )}
    
            {!loading && users.length > 0 && <UserList users={users} />}
          </div>
        </>
      );

}

export default App;