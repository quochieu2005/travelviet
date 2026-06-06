// src/services/blogApi.js
const API_URL = "http://localhost:8000/api";

class BlogApiService {
    constructor() {
        this.baseURL = API_URL;
    }

    async request(endpoint, options = {}) {
        const defaultOptions = {
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
        };

        const mergedOptions = { ...defaultOptions, ...options };

        try {
            const response = await fetch(`${this.baseURL}${endpoint}`, mergedOptions);

            if (!response.ok) {
                const errorData = await response.json().catch(() => ({}));
                throw new Error(errorData.message || `Lỗi ${response.status}`);
            }

            const data = await response.json();
            return data;
        } catch (error) {
            console.error('API Error:', error);
            throw error;
        }
    }

    // Lấy danh sách bài viết có phân trang
    async getPosts(params = {}) {
        const queryString = new URLSearchParams(params).toString();
        const endpoint = `/blog/posts${queryString ? `?${queryString}` : ''}`;
        return this.request(endpoint);
    }

    // Lấy bài viết nổi bật
    async getFeaturedPosts(limit = 3) {
        return this.request(`/blog/posts/featured?limit=${limit}`);
    }

    // Lấy chi tiết bài viết
    async getPostBySlug(slug) {
        return this.request(`/blog/posts/${slug}`);
    }

    // Lấy danh sách categories
    async getCategories() {
        return this.request('/blog/categories');
    }

    // Lấy bài viết liên quan
    async getRelatedPosts(postId, limit = 3) {
        return this.request(`/blog/posts/${postId}/related?limit=${limit}`);
    }
}

export default new BlogApiService();