import axios from "axios";

const API_URL = 'http://localhost:8000/api';

export const sendContact = async (data) => {
    const token = localStorage.getItem("token");

    const response = await axios.post(
        `${API_URL}/contacts`,
        data,
        {
            headers: {
                Authorization: `Bearer ${token}`,
                Accept: "application/json",
            },
        }
    );

    return response.data;
};