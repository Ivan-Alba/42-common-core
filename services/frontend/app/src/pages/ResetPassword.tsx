import React, { useState } from "react";
import { useNavigate, useSearchParams } from "react-router-dom";
import { useTranslation } from "react-i18next";
import AuthLayout from "../components/layouts/AuthLayout";
import InputGroup from "../components/ui/InputGroup";
import AlertSuccess from "../components/ui/AlertSuccess";
import { validateEmail, validatePassword } from "../utils/validators";
import axios from "axios";
import api from "../services/api";

const ResetPassword = () => {
    const { t } = useTranslation();
    const navigate = useNavigate();

    /* Reading URL to check if is redirecting from email */
    const [searchParams] = useSearchParams();
    const token = searchParams.get('token');
    const emailFromUrl = searchParams.get('email');
    
    /* If there is a token on URL, can change password */
    const isResetMode = !!token;

    /* State to verify if email is on database */
    const [isEmailed, setIsEmailed] = useState(false);
    const [isLoading, setIsLoading] = useState(false);
    const [isPasswordChanged, setIsPasswordChanged] = useState(false);
    const [serverError, setServerError] = useState("");

    /* Inputs States */
    const [formData, setFormData] = useState({ 
        email: emailFromUrl || "", 
        password: "", 
        confirm_password: "" 
    });
    const [errors, setErrors] = useState({ email: "", password: "", confirm_password: "" });

    /* Handle Input Change */
    const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const { name, value } = e.target;
        setFormData({ ...formData, [name]: value });
        if (errors[name as keyof typeof errors]) setErrors({ ...errors, [name]: "" });
        /* Cleaning errors */
        setServerError("");
    };

    /* Validation Function by steps if mails exist the inputs to change passwords will be shown */
    const validate = (step: 'email' | 'password') => {
        /* First step: validate email */
        if (step === 'email') {
            const emailError = validateEmail(formData.email.trim(), t);
            
            /* Refresh component if email error changes */
            setErrors(prev => ({ ...prev, email: emailError }));
            
            return (!emailError);
        }

        /* Second step: validate password and confirm password */
        if (step === 'password') {
            const passwordError = validatePassword(formData.password, t);
            const confirmError = formData.password !== formData.confirm_password 
                ? t("validation.passwords_match") 
                : "";

            /* Refresh component if password errors change */
            setErrors(prev => ({ 
                ...prev, 
                password: passwordError, 
                confirm_password: confirmError 
            }));

            /* Returns true if both are clean */
            return (!passwordError && !confirmError);
        }

        return false;
    }; 
    
    /* Handle Form Submit (Phase 1: Ask for e-mail) */
    const handleForgotPassword = async (e: React.FormEvent) => {
        e.preventDefault();
        if (!validate('email')) return;
        
        setIsLoading(true);
        setServerError("");

        try {
            await api.get('/sanctum/csrf-cookie');
            await api.post('/forgot-password', { 
                email: formData.email.trim().toLowerCase() 
            });
            // Aunque el email no exista, Laravel por seguridad suele devolver 200 OK
            // para no revelar qué correos están registrados a posibles atacantes. CONSULTAR CON KEVIN
            setIsEmailed(true);
        } catch (error) {
            console.error("Error pidiendo email:", error);
            // Si Laravel decide devolver 422 para emails no encontrados:
            if (axios.isAxiosError(error) && error.response?.status === 422) {
                setErrors(prev => ({ ...prev, email: t("validation.email_not_registered") }));
            } else {
                setServerError(t("errors.unexpected"));
            }
        } finally {
            setIsLoading(false);
        }
    };

    /* Handle Form Submit (Phase 2: Change Password) */
    const handleResetPassword = async (e: React.FormEvent) => {
        e.preventDefault();
        if (!validate('password')) return;
        
        setIsLoading(true);
        setServerError("");

        try {
            /* Standard Fortufy request to reset */
            await api.get('/sanctum/csrf-cookie');
            await api.post('/reset-password', {
                token: token,
                email: formData.email,
                password: formData.password,
                password_confirmation: formData.confirm_password
            });
            
            setIsPasswordChanged(true);
            setTimeout(() => navigate("/signin"), 4000);
            
        } catch (error) {
            console.error("Error:", error);
            if (axios.isAxiosError(error) && error.response?.status === 422) {
                setServerError(t('errors.link_invalid'));
            } else {
                setServerError(t("errors.unexpected"));
            }
        } finally {
            setIsLoading(false);
        }
    };

    const pageTitle = isPasswordChanged ? "¡Hecho!" : t("password.title");
    const pageSubtitle = isPasswordChanged ? "Redirigiendo..." : t("password.subtitle");

    return (
        <AuthLayout title={pageTitle} subtitle={pageSubtitle}>
            
            {/* General Server Error Message */}
            {serverError && (
                <div className="mb-4 p-3 bg-danger/10 border border-danger/20 text-danger rounded text-sm text-center">
                    {serverError}
                </div>
            )}

            {/* If Success */}
            {isPasswordChanged ? (
                <AlertSuccess title={`${t("password.success")} ${formData.email}`} message={t("password.back_login")} />
            
            // If reset come from e-mail
            ) : isResetMode ? (
                <form className="space-y-4 animate-fade-in" onSubmit={handleResetPassword} noValidate>
                    <InputGroup label={t("common.email")} type="email" name="email" value={formData.email} disabled={true} onChange={() => {}} />
                    <InputGroup label={t("common.password")} type="password" name="password" placeholder="••••••••" value={formData.password} onChange={handleChange} error={errors.password} />
                    <InputGroup label={t("common.confirm_password")} type="password" name="confirm_password" placeholder="••••••••" value={formData.confirm_password} onChange={handleChange} error={errors.confirm_password} className="mb-8" />
                    
                    <button type="submit" disabled={isLoading} className={`btn-primary-full ${isLoading ? 'bg-slate-600 cursor-wait' : ''}`}>
                        {isLoading ? t("password.processing") : t("password.enter")}
                    </button>
                </form>

            /* If user is in the "Request Email" mode  */
            ) : !isEmailed ? (
                <form className="space-y-4 animate-fade-in" onSubmit={handleForgotPassword} noValidate>
                    <InputGroup label={t("common.email")} type="email" name="email" placeholder="email@email.com" value={formData.email} onChange={handleChange} disabled={isLoading} error={errors.email} className="mb-8" />
                    
                    <button type="submit" disabled={isLoading} className={`btn-primary-full ${isLoading ? 'bg-slate-600 cursor-wait' : ''}`}>
                        {isLoading ? t("password.processing") : t("password.enter")}
                    </button>
                </form>
            
            // E-mail sent
            ) : (
                <div className="text-center animate-fade-in-down">
                    <AlertSuccess title={t("password.link_sent")} message={`${t("password.instructions_sent")} ${formData.email}. ${t("password.check_inbox")}`} />
                    <button onClick={() => navigate("/signin")} className="mt-6 text-slate-400 hover:text-brand-500 transition-colors underline text-sm">
                        {t("password.back_login")}
                    </button>
                </div>
            )}
        </AuthLayout>
    );
};

export default ResetPassword;