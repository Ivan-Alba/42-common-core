import React from 'react';

interface StatBoxProps {
    label: string;
    value: string | number;
    icon?: React.ReactNode;
    color?: string;
}

const StatBox = ({ label, value, icon, color = "text-white" }: StatBoxProps) => (
    <div className="glass-panel p-4 flex flex-col items-center justify-center text-center hover:bg-white/5 transition-colors">
        <span className={`flex text-3xl font-black mb-1 ${color}`}>{value}</span>
        <span className="text-xs text-slate-400 uppercase tracking-wider font-bold flex items-center gap-2">
            {icon} {label}
        </span>
    </div>
);

export default StatBox;