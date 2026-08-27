import React, { createContext, useContext, useState, useEffect } from 'react';
import AsyncStorage from '@react-native-async-storage/async-storage';
import api from '../api/client';

interface User {
  id: number;
  name: string;
  email: string;
  phone?: string;
  role?: string;
}

interface AuthContextType {
  user: User | null;
  token: string | null;
  isLoading: boolean;
  login: (email: string, password: string) => Promise<boolean>;
  register: (name: string, email: string, password: string, phone?: string) => Promise<boolean>;
  logout: () => Promise<void>;
  checkAuth: () => Promise<void>;
}

const AuthContext = createContext<AuthContextType | undefined>(undefined);

export const AuthProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const [user, setUser] = useState<User | null>(null);
  const [token, setToken] = useState<string | null>(null);
  const [isLoading, setIsLoading] = useState<boolean>(true);

  useEffect(() => {
    checkAuth();
  }, []);

  const checkAuth = async () => {
    try {
      const savedToken = await AsyncStorage.getItem('@auth_token');
      const savedUser = await AsyncStorage.getItem('@auth_user');
      if (savedToken && savedUser) {
        setToken(savedToken);
        setUser(JSON.parse(savedUser));
        
        // Fetch fresh profile in background
        api.get('/auth/profile').then(res => {
          if (res.data?.data) {
            setUser(res.data.data);
            AsyncStorage.setItem('@auth_user', JSON.stringify(res.data.data));
          }
        }).catch(() => {});
      }
    } catch (e) {
      console.error('Failed to load auth session:', e);
    } finally {
      setIsLoading(false);
    }
  };

  const login = async (email: string, password: string): Promise<boolean> => {
    try {
      const response = await api.post('/auth/login', { email, password });
      const { user: userData, token: userToken } = response.data.data;
      
      setUser(userData);
      setToken(userToken);
      
      await AsyncStorage.setItem('@auth_token', userToken);
      await AsyncStorage.setItem('@auth_user', JSON.stringify(userData));
      return true;
    } catch (error: any) {
      throw error.response?.data?.message || 'Gagal masuk akun. Periksa email & password.';
    }
  };

  const register = async (name: string, email: string, password: string, phone?: string): Promise<boolean> => {
    try {
      const response = await api.post('/auth/register', {
        name,
        email,
        password,
        password_confirmation: password,
        phone,
      });
      const { user: userData, token: userToken } = response.data.data;
      
      setUser(userData);
      setToken(userToken);
      
      await AsyncStorage.setItem('@auth_token', userToken);
      await AsyncStorage.setItem('@auth_user', JSON.stringify(userData));
      return true;
    } catch (error: any) {
      throw error.response?.data?.message || 'Gagal mendaftarkan akun.';
    }
  };

  const logout = async () => {
    try {
      await api.post('/auth/logout').catch(() => {});
    } finally {
      setUser(null);
      setToken(null);
      await AsyncStorage.removeItem('@auth_token');
      await AsyncStorage.removeItem('@auth_user');
    }
  };

  return (
    <AuthContext.Provider value={{ user, token, isLoading, login, register, logout, checkAuth }}>
      {children}
    </AuthContext.Provider>
  );
};

export const useAuth = () => {
  const context = useContext(AuthContext);
  if (!context) throw new Error('useAuth must be used within an AuthProvider');
  return context;
};
