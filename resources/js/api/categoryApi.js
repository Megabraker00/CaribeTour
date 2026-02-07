import axios from 'axios';

const API_BASE_URL = '/api/v1/categories';

export const fetchCategories = async () => {
    try {
        const response = await axios.get(API_BASE_URL);
        return response.data;
    } catch (error) {
        console.error('Error fetching categories:', error);
        throw error;
    }
};

export const fetchCategoryById = async (id) => {
    try {
        const response = await axios.get(`${API_BASE_URL}/${id}`);
        return response.data;
    } catch (error) {
        console.error(`Error fetching category with id ${id}:`, error);
        throw error;
    }
};

export const createCategory = async (categoryData) => {
    try {
        const response = await axios.post(API_BASE_URL, categoryData);
        return response.data;
    } catch (error) {
        console.error('Error creating category:', error);
        throw error;
    }
};

export const updateCategory = async (id, categoryData) => {
    try {
        const response = await axios.put(`${API_BASE_URL}/${id}`, categoryData);
        return response.data;
    } catch (error) {
        console.error(`Error updating category with id ${id}:`, error);
        throw error;
    }
};

export const deleteCategory = async (id) => {
    try {
        const response = await axios.delete(`${API_BASE_URL}/${id}`);
        return response.data;
    } catch (error) {
        console.error(`Error deleting category with id ${id}:`, error);
        throw error;
    }
};