// src/services/restaurantApi.js
const API_URL = "http://localhost:8000/api";

class RestaurantApiService {
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

    // Lấy danh sách nhà hàng và tự động bóc tách mảng dữ liệu từ Laravel Paginate
    async getRestaurants(params = {}) {
        const queryString = new URLSearchParams(params).toString();
        const endpoint = `/restaurants${queryString ? `?${queryString}` : ''}`;
        
        const response = await this.request(endpoint);
        
        if (response && response.data && response.data.data) {
            return response.data.data; 
        }
        if (response && response.data) {
            return response.data;
        }
        return response;
    }

    // Lấy chi tiết nhà hàng theo cấu trúc detail/{slug} thực tế dưới local
    async getRestaurantDetails(slug) {
        return this.request(`/restaurants/detail/${slug}`);
    }
}

export default new RestaurantApiService();