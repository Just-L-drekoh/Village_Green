import React from 'react';
import { Link, Outlet } from 'react-router-dom';

const Layout = () => {
    return (
        <div className="flex flex-col h-screen">
            <header className="bg-gray-800 text-white p-4">
                <div className="container mx-auto flex items-center justify-between">
                    <Link to="/admin/dashboard" className="text-xl font-semibold">Tableau de Bord</Link>
                    <nav>
                        <Link to="/orders" className="mx-2">Commandes</Link>
                        <Link to="/users" className="mx-2">Utilisateurs</Link>
                        <Link to="/turnover" className="mx-2">Chiffres d'affaire</Link>
                        <Link to="/turnoverSupplier" className="mx-2">CA Fournisseurs</Link>
                    </nav>
                </div>
            </header>
            <main className="container mx-auto flex-grow p-6">
                <h1 className="text-2xl font-semibold text-gray-800">Bienvenue sur le Tableau de bord</h1>
                <Outlet />
            </main>
        </div>
    );
};

export default Layout;
