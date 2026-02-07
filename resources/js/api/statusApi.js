import axios from 'axios';

const API_BASE_URL = '/api/v1/statuses';

export const fetchStatuses = async () => {
    try {
        const response = await axios.get(API_BASE_URL);
        return response.data;
    } catch (error) {
        console.error('Error fetching statuses:', error);
        throw error;
    }
};

export const fetchStatusById = async (id) => {
    try {
        const response = await axios.get(`${API_BASE_URL}/${id}`);
        return response.data;
    } catch (error) {
        console.error(`Error fetching status with id ${id}:`, error);
        throw error;
    }
};

export const createStatus = async (statusData) => {
    try {
        const response = await axios.post(API_BASE_URL, statusData);
        return response.data;
    } catch (error) {
        console.error('Error creating status:', error);
        throw error;
    }
};

export const updateStatus = async (id, statusData) => {
    try {
        const response = await axios.put(`${API_BASE_URL}/${id}`, statusData);
        return response.data;
    } catch (error) {
        console.error(`Error updating status with id ${id}:`, error);
        throw error;
    }
};

export const deleteStatus = async (id) => {
    try {
        const response = await axios.delete(`${API_BASE_URL}/${id}`);
        return response.data;
    } catch (error) {
        console.error(`Error deleting status with id ${id}:`, error);
        throw error;
    }
};