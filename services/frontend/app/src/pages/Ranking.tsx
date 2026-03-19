import { useState, useEffect } from 'react';
import { useTranslation } from 'react-i18next';
import { FaTrophy, FaSearch, FaMinus } from "react-icons/fa"; // Añadido FaMinus para el estado neutro
import { HiTrendingUp, HiTrendingDown } from "react-icons/hi";
import DashboardLayout from "../components/layouts/DashboardLayout";
import LoadingState from "../components/ui/LoadingState";
import PodiumCard from "../components/ui/PodiumCard";
import PlayerBadge from "../components/ui/PlayerBadge";
import userService from '../services/userService';
import type { PlayerStats, RankingUser } from '../models/RankingUser';
import { useAuth } from '../context/AuthContext';


const Ranking = () => {
    const { t } = useTranslation();
    const { user: authUser } = useAuth();
    
    const [isLoading, setIsLoading] = useState(true);
    const [rankingData, setRankingData] = useState<(RankingUser & { stats: PlayerStats })[]>([]);
    const [searchTerm, setSearchTerm] = useState("");

    useEffect(() => {
        const fetchRanking = async () => {
            setIsLoading(true);
            
            try {
                const data = await userService.getRanking();
				/* Force typing here because we know the ranking endpoint DOES include stats */
                setRankingData(data as (RankingUser & { stats: PlayerStats })[]);
            } catch (error) {
                console.error("Error fetching ranking:", error);
            } finally {
                setIsLoading(false);
            }
        };
        fetchRanking();
    }, []);

    const topThree = rankingData.slice(0, 3);
    const restOfPlayers = rankingData.slice(3).filter(player =>
        player.username.toLowerCase().includes(searchTerm.toLowerCase())
    );

    if (isLoading) return <DashboardLayout isCentered={true}><LoadingState message={t('common.loading')} /></DashboardLayout>;

    return (
        <DashboardLayout isCentered={false}>
            <div className="max-w-5xl mx-auto w-full animate-fade-in-up pb-24">

                {/* HEADER */}
                <div className="mb-10 text-center relative z-10">
                    <h1 className="text-4xl md:text-5xl font-bold text-white mb-2 flex items-center justify-center gap-3">
                        <FaTrophy className="text-brand-500 drop-shadow-[0_0_10px_rgba(59,130,246,0.6)]" />
                        <span className="text-white drop-shadow-[0_0_15px_rgba(59,130,246,0.5)] pb-2">
                            {t('ranking.title')}
                        </span>
                    </h1>
                    <p className="text-slate-400 max-w-lg mx-auto">{t('ranking.subtitle')}</p>
                </div>

                {/* PODIUM SECTION */}
                <div className="flex flex-col md:flex-row justify-center items-center md:items-end gap-1 md:gap-4 mb-12 w-full">
                    {/* 1ST PLACE */}
                    <div className="order-1 md:order-2 w-full md:w-1/3 flex justify-center z-10">
                        {topThree[0] && <PodiumCard player={topThree[0]} place={1} delay="0ms" isWinner />}
                    </div>

                    {/* 2ND PLACE */}
                    <div className="order-2 md:order-1 w-full md:w-1/3 flex justify-center">
                        {topThree[1] && <PodiumCard player={topThree[1]} place={2} delay="100ms" />}
                    </div>

                    {/* 3RD PLACE */}
                    <div className="order-3 md:order-3 w-full md:w-1/3 flex justify-center">
                        {topThree[2] && <PodiumCard player={topThree[2]} place={3} delay="200ms" />}
                    </div>
                </div>

                {/* SEARCH BAR */}
                <div className="glass-panel p-4 mb-6 flex items-center gap-3">
                    <FaSearch className="text-slate-500 ml-2" />
                    <input
                        type="text"
                        placeholder={t('ranking.search_player')}
                        value={searchTerm}
                        onChange={(e) => setSearchTerm(e.target.value)}
                        className="bg-transparent border-none text-white placeholder-slate-500 focus:ring-0 w-full outline-none"
                    />
                </div>

                {/* --- MOBILE VIEW (2 LINES) --- */}
                <div className="grid gap-3 lg:hidden">
                    {restOfPlayers.map((player, index) => {
						/* Compare player ID with logged-in user ID to determine if this card is the current user */
						const isCurrentUser = authUser?.id ? Number(authUser.id) === Number(player.id) : false;
                        
                        return (
                            <div key={player.id} className={`glass-panel p-4 relative overflow-hidden ${isCurrentUser ? "border-brand-500/50 bg-brand-500/5" : ""}`}>

                                {/* LINE 1: Position + User */}
                                <div className="flex items-center gap-3 mb-3">
                                    <div className="font-mono text-xl font-bold text-slate-500 w-8 text-center shrink-0">
                                        #{index + 4}
                                    </div>
                                    <div className="flex-1">
                                        <PlayerBadge
                                            avatar={player.avatar ? player.avatar : undefined}
                                            name={player.username}
                                            isCurrentUser={isCurrentUser}
                                            className="justify-start! [&>div:first-child]:w-auto [&>div:first-child]:pr-3 [&>div:last-child]:w-auto [&>div:last-child]:pl-3"
                                        />
                                    </div>
                                </div>

                                {/* Separator */}
                                <div className="h-px bg-white/5 w-full mb-3"></div>

                                {/* Line 2: Data */}
                                <div className="flex justify-between items-center px-2">
                                    <div className="flex items-center gap-2 text-xs text-slate-400">
                                        <span>{t('profile.wins')}: <span className="text-white font-bold">{player.stats.wins}</span></span>

                                        <div className="flex items-center justify-center gap-1 ml-2 bg-black/20 px-2 py-1 rounded">
                                            {player.stats.last_rank_pos != null && player.stats.last_rank_pos > index + 4 && <HiTrendingUp className="text-success text-base" />}
                                            {player.stats.last_rank_pos != null && player.stats.last_rank_pos < index + 4 && <HiTrendingDown className="text-danger text-base" />}
                                            {player.stats.last_rank_pos != null && player.stats.last_rank_pos === index + 4 && <FaMinus className="text-slate-500 text-xs" />}
                                        </div>
                                    </div>

                                    <div className="text-right">
                                        <span className="text-xs text-slate-500 block">{t('ranking.points')}</span>
                                        <span className="text-white font-mono font-bold text-lg tracking-wider">
                                            {player.stats.ranked_points}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        );
                    })}
                </div>

                {/* DESKTOP TABLE */}
                <div className="hidden lg:block glass-panel overflow-hidden mb-12">
                    <table className="w-full text-left text-sm text-slate-400">
                        <thead className="bg-white/5 text-slate-200 uppercase text-xs font-bold border-b border-white/10">
                            <tr>
                                <th className="px-6 py-4 text-center w-16">#</th>
                                <th className="px-6 py-4 text-center">{t('common.name')}</th>
                                <th className="px-6 py-4 text-center w-44">{t('ranking.trend')}</th>
                                <th className="px-6 py-4 text-center w-44">{t('profile.wins')}</th>
                                <th className="px-6 py-4 text-right w-48">{t('ranking.points')}</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-white/5">
                            {restOfPlayers.map((player, index) => {
								/* Compare player ID with logged-in user ID to determine if this row is the current user */
                                const isCurrentUser = authUser?.id ? Number(authUser.id) === Number(player.id) : false;
                                return (
                                    <tr key={player.id} className={`hover:bg-white/5 transition-colors group ${isCurrentUser ? "bg-brand-500/10" : ""}`}>
                                        <td className="px-6 py-4 text-center font-mono font-bold text-slate-500 group-hover:text-white transition-colors">{index + 4}</td>
                                        <td className="px-6 py-4">
                                            <PlayerBadge
                                                avatar={player.avatar ? player.avatar : undefined}
                                                name={player.username}
                                                isCurrentUser={isCurrentUser}
                                                className="justify-center!"
                                            />
                                        </td>
                        
                                        <td className="px-6 py-4 text-center">
                                            <div className="flex justify-center items-center">
                                                {player.stats.last_rank_pos != null && player.stats.last_rank_pos > index + 4 && <HiTrendingUp className="text-success text-xl" />}
                                                {player.stats.last_rank_pos != null && player.stats.last_rank_pos < index + 4 && <HiTrendingDown className="text-danger text-xl" />}
                                                {player.stats.last_rank_pos != null && player.stats.last_rank_pos === index + 4 && <FaMinus className="text-slate-500 text-sm" />}
                                            </div>
                                        </td>
                                        <td className="px-6 py-4 text-center text-slate-300 font-bold">{player.stats.wins}</td>
                                        <td className="px-6 py-4 text-right font-mono font-bold text-white tracking-wider text-base">{player.stats.ranked_points}</td>
                                    </tr>
                                );
                            })}
                        </tbody>
                    </table>
                    {restOfPlayers.length === 0 && <div className="p-8 text-center text-slate-500">{t('ranking.no_ranking')}</div>}
                </div>
            </div>
        </DashboardLayout>
    );
};

export default Ranking;