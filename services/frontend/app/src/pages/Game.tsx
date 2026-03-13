import { useParams, useNavigate } from 'react-router-dom';
import UnityGame from '../components/ui/UnityGame'; // Asumiendo que el componente de Unity de arriba lo guardaste aquí

const Game = () => {
    const { matchId } = useParams();
    const navigate = useNavigate();

    // Cuando Unity lance el evento "OnGameOver", esta función atrapará los datos
    const handleGameOver = (matchResult: any) => {
        console.log("Datos de la partida terminada:", matchResult);
        
        // Aquí podrías redirigir a una pantalla de "Resultados" pasándole los datos
        // o mostrar un modal de victoria/derrota. Por ahora, al Index.
        navigate('/index');
    };

    if (!matchId) {
        return <div className="text-white text-center mt-20">Error: Match ID no encontrado</div>;
    }

    return (
        <div className="w-screen h-screen overflow-hidden bg-black">
            {/* El componente UnityGame cargará el canvas y enviará el MatchID a C# */}
            <UnityGame 
                matchId={matchId} 
                onGameOver={handleGameOver} 
            />
        </div>
    );
};

export default Game;