import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { useTranslation } from 'react-i18next';
import { FaTimes, FaRobot, FaUsers, FaTrophy, FaLock, FaBolt, FaShieldAlt } from 'react-icons/fa';
import { useAuth } from '../../context/AuthContext';
import { createPortal } from 'react-dom';

interface GameModeModalProps {
    isOpen: boolean;
    onClose: () => void;
}

const GameModeModal: React.FC<GameModeModalProps> = ({ isOpen, onClose }) => {
    const { t } = useTranslation();
    const navigate = useNavigate();
    const { user } = useAuth();
    
	/* Get user stats from unified model */
    const isRankedUnlocked = (user?.stats?.experience || 0) > 100;
    const maxCampaignPhase = user?.stats?.campaign || 1;

	/* State to control the selected phase in the dropdown */
    const [selectedPhase, setSelectedPhase] = useState<number>(1);

	/* Dropdown state for campaign phase selector */
	const [isDropdownOpen, setIsDropdownOpen] = useState<boolean>(false);

	/* Update selected campaign phase to the max available each time the modal opens */
    useEffect(() => {
        if (isOpen) {
            setSelectedPhase(maxCampaignPhase);
        }
    }, [isOpen, maxCampaignPhase]);

    const handleSelectMode = (mode: string, submode?: string) => {
        const queryParams = submode ? `?mode=${mode}&submode=${submode}` : `?mode=${mode}`;
        navigate(`/lobby${queryParams}`);
        onClose();
    };

    if (!isOpen) return null;

    return createPortal(
        <div className="modal-backdrop animate-fade-in-up">
            <div className="modal-content max-w-5xl">
            
                {/* Modal Header */}
                <div className="flex justify-between items-center p-6 border-b border-white/10 sticky top-0 bg-dark-900/80 backdrop-blur-md z-10">
                    <div>
                        <h2 className="text-2xl font-bold text-white flex items-center gap-3">
                            <FaBolt className="text-brand-500" />
                            {t('game_modes.title')}
                        </h2>
                        <p className="text-slate-400 text-sm mt-1">
                            {t('game_modes.subtitle')}
                        </p>
                    </div>
                    <button 
                        onClick={onClose} 
                        className="text-slate-400 hover:text-white p-2 rounded-lg hover:bg-white/5 transition-colors"
                    >
                        <FaTimes size={24} />
                    </button>
                </div>

                {/* Modal Body - Grid of Game Modes */}
                <div className="p-6 md:p-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                    
                    {/* 1. CAMPAIGN MODE (PvE) */}
                    <div className="card-interactive group">
                        <div className="w-14 h-14 bg-brand-500/20 text-brand-500 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <FaRobot size={28} />
                        </div>
                        <h3 className="text-xl font-bold text-white mb-2">{t('game_modes.campaign')}</h3>
                        <p className="text-sm text-slate-400 flex-1 mb-6">
                            {t('game_modes.campaign_desc')}
                        </p>
                        
                        <div className="bg-dark-900 border border-white/5 rounded-xl p-3 mb-6 text-xs text-slate-300 flex items-center justify-between">
                            <span>{t('game_modes.current_phase')}:</span>
                            
                            {/* Dinamic Dropdown for the Phase Selector */}
                            <div className="relative">
								{/* The "button" that shows the current phase and toggles the dropdown */}
                                <button 
                                    onClick={() => setIsDropdownOpen(!isDropdownOpen)}
                                    className="flex items-center gap-2 bg-transparent text-brand-500 font-bold hover:text-brand-400 transition-colors focus:outline-none"
                                >
                                    {t(`game_modes.campaign${selectedPhase}`)}
                                    <span className="text-[10px]">▼</span>
                                </button>

								{/* List of options only if dropdown is open */}
                                {isDropdownOpen && (
                                    <>
										{/* Invisible background to close the menu when clicking outside */}
                                        <div 
                                            className="fixed inset-0 z-40" 
                                            onClick={() => setIsDropdownOpen(false)}
                                        ></div>

										{/* Div to handle dropdown direction and positioning */}
                                        <div className="absolute right-0 bottom-full mb-2 w-48 bg-dark-900 border border-white/10 rounded-xl shadow-2xl overflow-hidden z-50 flex flex-col">
                                            {Array.from({ length: maxCampaignPhase }, (_, i) => i + 1).map((phaseNumber) => (
                                                <button
                                                    key={phaseNumber}
                                                    onClick={() => {
                                                        setSelectedPhase(phaseNumber);
                                                        setIsDropdownOpen(false);
                                                    }}
                                                    className={`w-full text-right px-4 py-3 text-sm font-bold transition-colors hover:bg-white/10 ${
                                                        selectedPhase === phaseNumber ? 'text-brand-500 bg-white/5' : 'text-slate-300'
                                                    }`}
                                                >
                                                    {t(`game_modes.campaign${phaseNumber}`)}
                                                </button>
                                            ))}
                                        </div>
                                    </>
                                )}
                            </div>
                        </div>

                        <button 
                            onClick={() => handleSelectMode(`CAMPAIGN_${selectedPhase}`)}
                            className="btn-primary w-full py-3 rounded-xl font-bold mt-auto"
                        >
                            {t('common.continue')}
                        </button>
                    </div>

                    {/* 2. CASUAL MODE (PvP) */}
                    <div className="card-interactive group">
                        <div className="w-14 h-14 bg-blue-500/20 text-blue-400 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <FaUsers size={28} />
                        </div>
                        <h3 className="text-xl font-bold text-white mb-2">{t('game_modes.casual')}</h3>
                        <p className="text-sm text-slate-400 flex-1 mb-6">
                            {t('game_modes.casual_desc')}
                        </p>
                        
                        <div className="mt-auto space-y-3">
                            <button 
                                onClick={() => handleSelectMode('PVP_CASUAL_UNLIMITED')}
                                className="w-full bg-dark-900 hover:bg-white/10 border border-white/10 text-white py-3 rounded-xl font-bold text-sm transition-colors flex justify-between items-center px-4"
                            >
                                <span>{t('game_modes.unlimited')}</span>
                                <span className="text-xs text-slate-500 font-normal">{t('game_modes.no_cost_limit')}</span>
                            </button>
                            <button 
                                onClick={() => handleSelectMode('PVP_CASUAL_LIMITED')}
                                className="w-full bg-dark-900 hover:bg-white/10 border border-white/10 text-white py-3 rounded-xl font-bold text-sm transition-colors flex justify-between items-center px-4"
                            >
                                <span>{t('game_modes.limited')}</span>
                                <span className="text-xs text-slate-500 font-normal">{t('game_modes.cost_limit')}</span>
                            </button>
                        </div>
                    </div>

                    {/* 3. RANKED MODE (PvP) */}
                    <div className={`bg-dark-800/50 border rounded-2xl p-6 flex flex-col transition-all duration-300 group relative overflow-hidden
                        ${isRankedUnlocked ? 'border-warning/50 hover:-translate-y-1 hover:border-warning hover:shadow-[0_0_15px_rgba(255,186,0,0.1)]' : 'border-white/5 opacity-70 cursor-not-allowed'}`}
                    >
                        {!isRankedUnlocked && (
                            <div className="absolute inset-0 bg-dark-900/60 backdrop-blur-[2px] z-10 flex flex-col items-center justify-center">
                                <FaLock className="text-slate-500 mb-3" size={32} />
                                <span className="text-slate-400 text-sm font-bold px-6 text-center">
                                    {t('game_modes.locked_ranked')}
                                </span>
                            </div>
                        )}

                        <div className="w-14 h-14 bg-warning/20 text-warning rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <FaTrophy size={28} />
                        </div>
                        <h3 className="text-xl font-bold text-white mb-2">{t('game_modes.ranked')}</h3>
                        <p className="text-sm text-slate-400 flex-1 mb-6">
                            {t('game_modes.ranked_desc')}
                        </p>

                        <div className="bg-dark-900 border border-white/5 rounded-xl p-3 mb-6 text-xs text-slate-300 flex items-center justify-between">
                            <span className="flex items-center gap-2"><FaShieldAlt className="text-slate-500"/> {t('game_modes.deck_limit')}:</span>
                            <span className="font-bold text-white">4 pts</span>
                        </div>

                        <button 
                            disabled={!isRankedUnlocked}
                            onClick={() => handleSelectMode('PVP_RANKED')}
                            className={`w-full py-3 rounded-xl font-bold mt-auto transition-colors ${isRankedUnlocked ? 'bg-warning hover:bg-warning/80 text-dark-900' : 'bg-dark-900 text-slate-500 border border-white/10'}`}
                        >
                            {t('game_modes.find_match')}
                        </button>
                    </div>

                </div>
            </div>
        </div>,
        document.body
    );
};

export default GameModeModal;