import React, { useState, children, createContext, useEffect } from "react";

export const CartContext = createContext();

export const CartProvider = ({ children }) => {
    const [cartItems, setCartItems] = useState(() => {
        const stored = localStorage.getItem('cart');
        return stored ? JSON.parse(stored) : []
    });

    useEffect(() => {
        localStorage.setItem('cart', JSON.stringify(cartItems));
    }, [cartItems]);

    const addTOCart = (item) => {
        const exists = cartItems.find(cart => cart.id === item.id);
        if (!exists) {
            setCartItems(prev => [...prev, item])
        }
    }

    const RemoveFromCart = (id) => {
        setCartItems(prev => prev.filter(item => item.id !== id));
    };

    const updateCart = (id, updatedItem) => {
        setCartItems(prev =>
            prev.map(cart => cart.id === id ? { ...cart, ...updatedItem } : cart)
        );
    };

    return (
        <CartContext.Provider value={{ cartItems, addTOCart, RemoveFromCart, updateCart }}>
            {children}
        </CartContext.Provider>
    )
}