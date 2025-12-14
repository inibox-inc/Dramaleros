// src/App.jsx
import React, { useState } from 'react';
import ListaItems from './components/ListaItems';
// Importa los datos de la carpeta data
import itemsData from './data/data.json'; 

// --- Estilos de la Aplicación ---
const appContainerStyle = {
    fontFamily: "'Helvetica Neue', Arial, sans-serif",
    textAlign: 'center',
    padding: '30px',
    background: '#1c2534', // Fondo muy oscuro
    minHeight: '100vh',
    color: '#ecf0f1',
};

const filtroContainerStyle = {
    marginBottom: '30px',
    display: 'flex',
    justifyContent: 'center',
    gap: '15px',
};

const listContainerStyle = {
    display: 'flex',
    flexWrap: 'wrap',
    justifyContent: 'center',
    gap: '25px',
};

const buttonBaseStyle = {
    padding: '12px 25px',
    border: 'none',
    borderRadius: '6px',
    cursor: 'pointer',
    fontWeight: 'bold',
    transition: 'background-color 0.2s, box-shadow 0.2s',
};

const buttonStyle = {
    ...buttonBaseStyle,
    background: '#3498db', // Azul primario
    color: 'white',
};

const activeButtonStyle = {
    ...buttonBaseStyle,
    background: '#e74c3c', // Rojo activo
    color: 'white',
    boxShadow: '0 0 12px rgba(231, 76, 60, 0.7)',
};

const App = () => {
  // 1. Estado de los datos: Se inicializa con los datos importados.
  const [items] = useState(itemsData);
  // 2. Estado de Filtrado: 'Todos', 'Película', o 'Serie'.
  const [filtroTipo, setFiltroTipo] = useState('Todos');

  // Lógica para filtrar la lista
  const itemsFiltrados = items.filter(item => {
    if (filtroTipo === 'Todos') {
      return true;
    }
    return item.tipo === filtroTipo;
  });

  // Función auxiliar para renderizar el botón de filtro con el estilo correcto
  const renderFilterButton = (label, tipo) => (
    <button 
      onClick={() => setFiltroTipo(tipo)}
      style={filtroTipo === tipo ? activeButtonStyle : buttonStyle}
    >
      {label}
    </button>
  );

  return (
    <div style={appContainerStyle}>
      
      <h1>🎬 Catálogo Simple</h1>
      
      {/* Controles de Filtrado */}
      <div style={filtroContainerStyle}>
        {renderFilterButton('Mostrar Todo', 'Todos')}
        {renderFilterButton('Solo Películas', 'Película')}
        {renderFilterButton('Solo Series', 'Serie')}
      </div>
      
      <h2>Resultados ({itemsFiltrados.length})</h2>

      {/* Renderiza la lista de ítems filtrados */}
      <div style={listContainerStyle}>
        {itemsFiltrados.map(item => (
          <ListaItems key={item.id} item={item} />
        ))}
      </div>
      
    </div>
  );
};

export default App;
