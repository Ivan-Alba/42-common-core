import { useState, useEffect } from 'react';

/* Put the last date we received from the backend as the targetDate, and this hook will give us a live countdown in "MM:SS" format until that date. Once the date is reached, it will show "00:00" and set isFinished to true. */
export const useCountdown = (targetDate: string | null) => {
    const [timeLeft, setTimeLeft] = useState<string | null>(null);
    const [isFinished, setIsFinished] = useState(false);

    useEffect(() => {
        if (!targetDate) {
            setTimeLeft(null);
            setIsFinished(false);
            return;
        }

        const targetTime = new Date(targetDate).getTime();

        const updateTimer = () => {
            const now = new Date().getTime();
            const difference = targetTime - now;

            if (difference <= 0) {
                setTimeLeft("00:00");
                setIsFinished(true);
            } else {
                // Calculamos minutos y segundos
                const minutes = Math.floor((difference % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((difference % (1000 * 60)) / 1000);
                
                // Formateamos para que siempre tenga 2 dígitos (ej. 05:09)
                setTimeLeft(
                    `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`
                );
            }
        };

        // Ejecutamos una vez al instante para no esperar 1 segundo a que aparezca
        updateTimer();
        
        // Actualizamos cada segundo
        const interval = setInterval(updateTimer, 1000);

        // Limpieza al desmontar
        return () => clearInterval(interval);
    }, [targetDate]);

    return { timeLeft, isFinished };
};