const API_URL = 'http://localhost:8000/api';

class HotelService {
    async getHotels() {
        try {
            const response = await fetch(`${API_URL}/hotels`);
            if (!response.ok) {
                throw new Error('Failed to fetch hotels');
            }
            const data = await response.json();
            return data.hotels || [];
        } catch (error) {
            console.error('Error fetching hotels:', error);
            throw error;
        }
    }

    async getHotelBySlug(slug) {
        try {
            const response = await fetch(`${API_URL}/hotels/${slug}`);
            if (!response.ok) {
                throw new Error('Failed to fetch hotel');
            }
            const data = await response.json();
            return data;
        } catch (error) {
            console.error('Error fetching hotel:', error);
            throw error;
        }
    }
}

export default new HotelService();