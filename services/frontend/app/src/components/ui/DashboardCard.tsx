import React from 'react';

interface DashboardCardProps {
    title: string;
    subtitle: string;
    icon: React.ReactElement;
    onClick?: () => void;
    variant?: 'primary' | 'secondary';
    disabled?: boolean; // <-- ¡NUEVO! Le enseñamos a aceptar disabled
}

const DashboardCard = ({ title, subtitle, icon, onClick, variant = 'secondary', disabled = false }: DashboardCardProps) => {
    
    /* Common Structure Styles */ 
    const baseStyles = "relative group flex items-center gap-4 lg:gap-6 p-3 md:p-6 rounded-2xl border transition-all duration-300 overflow-hidden text-left w-full h-32 md:h-40";
    
    /* Styles based on variant AND disabled state */
    let variantStyles = "";
    let iconColor = "";

    if (disabled) {
        // Estilos para cuando está penalizado (Rojo, sin hover y cursor bloqueado)
        variantStyles = "bg-dark-900 border-danger/30 cursor-not-allowed opacity-80";
        iconColor = "text-danger";
    } else if (variant === 'primary') {
        variantStyles = "card-primary-glow transform hover:scale-[1.02] cursor-pointer";
        iconColor = "text-white";
    } else {
        variantStyles = "glass-panel glass-panel-hover transform hover:scale-[1.02] cursor-pointer";
        iconColor = "text-brand-500";
    }

    return (
        <button 
            onClick={onClick} 
            disabled={disabled} // Bloqueamos el clic real
            className={`${baseStyles} ${variantStyles}`}
        >
            
            <div className={`p-3 ${iconColor} shrink-0 ${!disabled ? 'transition-transform group-hover:scale-110 duration-300' : ''}`}>
                {React.cloneElement(icon as React.ReactElement<any>, { size: 50 })}
            </div>
            
            <div className="flex flex-col z-10">
                <h3 className={`text-2xl md:text-3xl font-bold tracking-wide font-sans ${disabled ? 'text-danger' : 'text-white'}`}>
                    {title}
                </h3>
                <p className={`text-sm md:text-base font-medium ${disabled ? 'text-danger/80 font-mono font-black text-lg' : (variant === 'primary' ? 'text-blue-100' : 'text-slate-400')}`}>
                    {subtitle}
                </p>
            </div>
        </button>
    );
};

export default DashboardCard;