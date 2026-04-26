import { createContext, useContext, useState, useEffect, type ReactNode } from 'react';
import type { User, AuthContextType } from './Auth';
import authService from '../services/authService';
import type { LoginCredentials, RegisterCredentials } from './Auth';
import i18n from '../i18n';

/* Creamos el contexto de autenticación con un valor inicial undefined, lo que nos ayudará a detectar si el hook se usa fuera del provider. */
const AuthContext = createContext<AuthContextType | undefined>(undefined);

/* Este componente envolverá toda la aplicación y proporcionará el estado de autenticación a través del contexto. */
export const AuthProvider = ({ children }: { children: ReactNode }) => {
    const [user, setUser] = useState<User | null>(null);
    // Iniciamos isLoading en true para que la app espere a verificar la sesión antes de mostrar nada
    const [isLoading, setIsLoading] = useState<boolean>(true);

	// Función para sincronizar el idioma del usuario con i18n
	const syncLanguage = (dbLanguage?: string) => {
        if (dbLanguage) {
            i18n.changeLanguage(dbLanguage);
        }
    };

	/* Esta función se encargará de verificar si el usuario ya tiene una sesión activa al cargar la página. */
    const checkSession = async () => {
		/* Para evitar hacer una petición innecesaria al backend cada vez que recargamos, podemos usar un flag en localStorage que indique si el usuario ha iniciado sesión antes. Esto no es 100% seguro, pero ayuda a reducir la cantidad de peticiones al backend. Si el flag no está presente, asumimos que no hay sesión y no hacemos la petición. Si el flag está presente, intentamos obtener el usuario para verificar si la sesión sigue activa. Así evito el error de consola ya que el backend me devuelve un 401 si no hay sesión. Todos los codigos 4xx y 5xx muestran error en consola, con esto evito al backend falsear el 401 por un 200(null) return response()->json(null); desde php */
		const isLoggedFlag = localStorage.getItem('is_logged_in');

		/* Si el flag no está presente, no hacemos la petición y simplemente establecemos isLoading en false. */
		if (!isLoggedFlag) {
            setIsLoading(false);
            return;
        }

        try {
            // Intentamos obtener el usuario al cargar la página
            const userData = await authService.getUser();
            setUser(userData);
			syncLanguage(userData.language);
        } catch (error: any) {
            // Si falla (401 Unauthorized), significa que no hay sesión o expiró
            // No es un error, simplemente no está logueado para evitar el error en consola, eliminamos el flag de localStorage y limpiamos el estado de usuario
			if (error.response && error.response.status === 401) 
                localStorage.removeItem('is_logged_in');
            setUser(null);
        } finally {
            setIsLoading(false);
        }
    };

    useEffect(() => {
        checkSession();
    }, []);

    /* Real Login */
    const login = async (credentials: LoginCredentials) => { 
        try {
            setIsLoading(true);
            const userResponse = await authService.login(credentials);
            
            localStorage.setItem('is_logged_in', 'true');
            
            /* Syncronize language after login */
            syncLanguage(userResponse.language);
            
            setUser(userResponse);
        } catch (error) {
            throw error;
        } finally {
            setIsLoading(false);
        }
    };

	/* Register REAL */
	const register = async (credentials: RegisterCredentials) => { 
        try {
            setIsLoading(true);
            const userResponse = await authService.register(credentials);
		
            localStorage.setItem('is_logged_in', 'true');
            
            syncLanguage(userResponse.language);
            
            setUser(userResponse);
        } catch (error) {
            throw error;
        } finally {
            setIsLoading(false);
        }
    };

    // Real Logout
    const logout = async () => {
        try {
            await authService.logout();
        } catch (error) {
            console.error("Error: ", error);
        } finally {
            setUser(null);
            localStorage.removeItem('is_logged_in');
        }
    };

	/* Context Provider send state and functions to children */
    return (
        <AuthContext.Provider value={{ 
            user, 
            isAuthenticated: !!user,
            setUser,
            isLoading, 
            login,
			register,
            logout 
        }}>
            {children}
        </AuthContext.Provider>
    );
};

/* This hook provides a convenient way to access the authentication context from any component. */
export const useAuth = () => {
    const context = useContext(AuthContext);
    if (!context) throw new Error('useAuth should be used within an AuthProvider');
    return context;
};

export default AuthProvider;