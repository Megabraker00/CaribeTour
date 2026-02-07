import axios from 'axios';

const API_BASE_URL = '/api/v1/types';

export const fetchTypes = async () => {
    try {
        const response = await axios.get(API_BASE_URL);
        return response.data;
    } catch (error) {
        console.error('Error fetching types:', error);
        throw error;
    }
};

export const fetchTypeById = async (id) => {
    try {
        const response = await axios.get(`${API_BASE_URL}/${id}`);
        return response.data;
    } catch (error) {
        console.error(`Error fetching type with id ${id}:`, error);
        throw error;
    }
};

export const createType = async (typeData) => {
    try {
        const response = await axios.post(API_BASE_URL, typeData);
        return response.data;
    } catch (error) {
        console.error('Error creating type:', error);
        throw error;
    }
};

export const updateType = async (id, typeData) => {
    try {
        const response = await axios.put(`${API_BASE_URL}/${id}`, typeData);
        return response.data;
    } catch (error) {
        console.error(`Error updating type with id ${id}:`, error);
        throw error;
    }
};

export const deleteType = async (id) => {
    try {
        const response = await axios.delete(`${API_BASE_URL}/${id}`);
        return response.data;
    } catch (error) {
        console.error(`Error deleting type with id ${id}:`, error);
        throw error;
    }
};