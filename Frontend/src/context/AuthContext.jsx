import React, { createContext, useContext, useState, useEffect } from 'react';

const AuthContext = createContext();

export const useAuth = () => useContext(AuthContext);

export const AuthProvider = ({ children }) => {
    const [user, setUser] = useState(null);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const stored = localStorage.getItem('user');
        setUser(stored ? JSON.parse(stored) : null);
        setLoading(false);

        const handleStorageChange = () => {
            const newStored = localStorage.getItem('user');
            setUser(newStored ? JSON.parse(newStored) : null);
        };

        window.addEventListener('storage', handleStorageChange);
        window.addEventListener('userLogin', handleStorageChange);
        window.addEventListener('userLogout', handleStorageChange);

        return () => {
            window.removeEventListener('storage', handleStorageChange);
            window.removeEventListener('userLogin', handleStorageChange);
            window.removeEventListener('userLogout', handleStorageChange);
        };
    }, []);

    const logout = () => {
        localStorage.removeItem('user');
        localStorage.removeItem('token');
        setUser(null);
        window.dispatchEvent(new Event('userLogout'));
    };

    return (
        <AuthContext.Provider value={{ user, setUser, logout, loading }}>
            {children}
        </AuthContext.Provider>
    );
};