// src/components/LanguageSelector.tsx
// import React from 'react';
import { useTranslation } from 'react-i18next';
import { useAuth } from '../context/AuthContext';
import userService from '../services/userService';

interface LanguageSelectorProps {
    className?: string; // Use props to put style from out of the component
}

const LanguageSelector = ({ className = "" }: LanguageSelectorProps) => {
    const { i18n } = useTranslation();
	const { user } = useAuth();

    const changeLanguage = async (lng: string) => {
        i18n.changeLanguage(lng);
        /* Update user's language preference in the backend */
        if (user) {
			try {
				await userService.updateLanguage(lng as 'en' | 'es' | 'ca');
			}
			catch (error) {
				// Silent
			}
        }
    };

    // Conditional auxiliar function to language selected style
    const getButtonClass = (lang: string) => {
        const isActive = i18n.language === lang;
        return `transition-colors hover:text-white ${isActive ? 'text-brand-500 font-bold' : 'text-slate-400'}`;
    };

    return (
        <div className={`flex gap-2 text-sm ${className}`}>
            <button onClick={() => changeLanguage('en')} className={getButtonClass('en')}>
                EN
            </button>
            <span className="text-slate-600">|</span>

            <button onClick={() => changeLanguage('es')} className={getButtonClass('es')}>
                ES
            </button>
            <span className="text-slate-600">|</span>

            <button onClick={() => changeLanguage('ca')} className={getButtonClass('ca')}>
                CA
            </button>
        </div>
    );
};

export default LanguageSelector;