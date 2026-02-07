import axios from 'axios';

const API_BASE_URL = '/api/v1/terminals';

export const fetchTerminals = async () => {
    try {
        const response = await axios.get(API_BASE_URL);
        return response.data;
    } catch (error) {
        console.error('Error fetching terminals:', error);
        throw error;
    }
};

export const fetchTerminalById = async (id) => {
    try {
        const response = await axios.get(`${API_BASE_URL}/${id}`);
        return response.data;
    } catch (error) {
        console.error(`Error fetching terminal with id ${id}:`, error);
        throw error;
    }
};

export const createTerminal = async (terminalData) => {
    try {
        const response = await axios.post(API_BASE_URL, terminalData);
        return response.data;
    } catch (error) {
        console.error('Error creating terminal:', error);
        throw error;
    }
};

export const updateTerminal = async (id, terminalData) => {
    try {
        const response = await axios.put(`${API_BASE_URL}/${id}`, terminalData);
        return response.data;
    } catch (error) {
        console.error(`Error updating terminal with id ${id}:`, error);
        throw error;
    }
};

export const deleteTerminal = async (id) => {
    try {
        const response = await axios.delete(`${API_BASE_URL}/${id}`);
        return response.data;
    } catch (error) {
        console.error(`Error deleting terminal with id ${id}:`, error);
        throw error;
    }
};