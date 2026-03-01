import axios from 'axios'
import { getSessionId } from './session'

const api = axios.create({
    baseURL: '/api',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },
})

api.interceptors.request.use((config) => {
    config.headers['X-Session-Id'] = getSessionId()
    return config
})

api.interceptors.response.use((response) => {
    response.data = response.data?.data ?? response.data
    return response
})

export default {
    getCalculations() {
        return api.get('/calculations')
    },
    calculate(expression) {
        return api.post('/calculations', { expression })
    },
    deleteCalculation(id) {
        return api.delete(`/calculations/${id}`)
    },
    clearAll() {
        return api.delete('/calculations')
    },
}
