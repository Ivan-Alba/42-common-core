import React, { useEffect, useState } from "react";
import { Link, useNavigate } from 'react-router-dom';
import { useTranslation } from 'react-i18next';
import AuthLayout from "../components/layouts/AuthLayout";
import InputGroup from "../components/ui/InputGroup";
import AlertSuccess from "../components/ui/AlertSuccess";
import { validateEmail, validatePassword, validateName } from "../utils/validators";
import { useAuth } from "../context/AuthContext";

const Register = () => {
    const { t } = useTranslation();
    const navigate = useNavigate();
    const [isRegistered, setIsRegistered] = useState(false);
	const { register, isAuthenticated } = useAuth();

	/* Redirect to /index if user is authenticated */
	useEffect(() => {
        if (isAuthenticated) {
            navigate('/index');
        }
    }, [isAuthenticated, navigate]);

    /* Inputs States */
    const [formData, setFormData] = useState({ username: "", email: "", password: "" });
    const [errors, setErrors] = useState({ username: "", email: "", password: "" });

    /* Handle Input Change */
    const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const { name, value } = e.target;
        setFormData({ ...formData, [name]: value });
        if (errors[name as keyof typeof errors]) setErrors({ ...errors, [name]: "" });
    };

    /* Validation Function */
    const validate = (data: typeof formData) => {
        /* Validate each field using utility functions */
        const nameError = validateName(data.username, t);
        const emailError = validateEmail(data.email, t);
        const passwordError = validatePassword(data.password, t);

        const newErrors = {
            username: nameError,
            email: emailError,
            password: passwordError
        };

        setErrors(newErrors);
		/* Returns true if all are clean */
        return (!nameError && !emailError && !passwordError);
    };

    /* Handle Form Submit */
    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        
        const cleanData = {
			username: formData.username.trim(),
            email: formData.email.trim(),
            password: formData.password,
			password_confirmation: formData.password,
			language: 'EN'
        };

        if (validate(cleanData)) {
            try {
				//console.log(cleanData);
                await register(cleanData);
                
                setIsRegistered(true);
                setTimeout(() => navigate('/index'), 5000000); 
                
            } catch (error: any) {
                
                //console.error("Error: ", error);
                
				if (error.response?.status === 404)
				{
					//console.log("Error 404");
				}

                if (error.response?.status === 422) {
					/* Laravel errors validation format is { errors: { field: [msg1, msg2] } } */
                    const serverErrors = error.response.data.errors;
                    if (serverErrors.email) {
                        setErrors(prev => ({ ...prev, email: t("validation.email_registered") }));
                    }
                    if (serverErrors.username) {
                        setErrors(prev => ({ ...prev, username: t("validation.name_taken") }));
                    }
                } else {
                    //console.log("Error", error);
                }
            }
        }
    }

    return (
        <AuthLayout title={t('register.title')} subtitle={t('register.subtitle')}>
            
            {isRegistered ? (
                <AlertSuccess title={t("register.success")} message={t("register.redirecting")} />
            ) : (
                <form className="space-y-4" noValidate onSubmit={handleSubmit}>
                    <InputGroup label={t('common.name')} type="text" name="username" placeholder="player1" value={formData.username} onChange={handleChange} error={errors.username} />
                    <InputGroup label={t('common.email')} type="email" name="email" placeholder="email@email.com" value={formData.email} onChange={handleChange} error={errors.email} />
                    <InputGroup label={t('common.password')} type="password" name="password" placeholder="••••••••" value={formData.password} onChange={handleChange} error={errors.password} className="mb-8" />

                    <button type="submit" className="btn-primary-full">
                        {t('common.register')}
                    </button>

                    <div className="mt-6 text-center">
                        <p className="text-slate-400 text-sm">
                            {t('register.account')}{' '}
                            <Link to="/signin" className="text-brand-500 font-bold hover:underline transition-colors">
                                {t('common.login')}
                            </Link>
                        </p>
                    </div>
                </form>
            )}
        </AuthLayout>
    );
};

export default Register;