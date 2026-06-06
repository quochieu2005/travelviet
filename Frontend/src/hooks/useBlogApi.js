// src/hooks/useBlogApi.js
import { useState, useEffect, useCallback } from 'react';
import blogApiService from '../services/blogApi';

export const useBlogPosts = (initialParams = {}) => {
    const [posts, setPosts] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [pagination, setPagination] = useState({
        currentPage: 1,
        lastPage: 1,
        perPage: 9,
        total: 0
    });
    const [params, setParams] = useState(initialParams);

    const fetchPosts = useCallback(async () => {
        setLoading(true);
        setError(null);

        try {
            const response = await blogApiService.getPosts(params);
            
            // Laravel trả về: { success, data: { data: [...], current_page, ... } }
            setPosts(response.data.data || []);
            setPagination({
                currentPage: response.data.current_page || 1,
                lastPage: response.data.last_page || 1,
                perPage: response.data.per_page || 9,
                total: response.data.total || 0
            });
        } catch (err) {
            setError(err.message);
            setPosts([]);
        } finally {
            setLoading(false);
        }
    }, [params]);

    useEffect(() => {
        fetchPosts();
    }, [fetchPosts]);

    const updateParams = (newParams) => {
        setParams(prev => ({ ...prev, ...newParams }));
    };

    const changePage = (page) => {
        setParams(prev => ({ ...prev, page }));
    };

    return {
        posts,
        loading,
        error,
        pagination,
        updateParams,
        changePage,
        refetch: fetchPosts
    };
};

export const useBlogCategories = () => {
    const [categories, setCategories] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        const fetchCategories = async () => {
            try {
                const response = await blogApiService.getCategories();
                setCategories(response.data || []);
            } catch (err) {
                setError(err.message);
                setCategories([]);
            } finally {
                setLoading(false);
            }
        };

        fetchCategories();
    }, []);

    return { categories, loading, error };
};