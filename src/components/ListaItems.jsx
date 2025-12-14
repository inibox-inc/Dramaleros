// src/components/ListaItems.jsx
import React from 'react';

// Estilos internos (puedes moverlos a index.css o usar un módulo CSS)
const cardStyle = {
  background: '#2c3e50', 
  color: '#ecf0f1', 
  padding: '20px',
  margin: '10px',
  borderRadius: '10px',
  boxShadow: '0 6px 12px rgba(0, 0, 0, 0.3)',
  minWidth: '280px',
  transition: 'transform 0.2s',
  borderLeft: '4px solid #3498db',
};

const headerStyle = {
  fontSize: '1.4rem',
  marginBottom: '10px',
  borderBottom: '1px solid #34495e',
  paddingBottom: '5px',
  fontWeight: '600',
};

const detailsStyle = {
    margin: '8px 0',
    fontSize: '0.95rem',
};

const ratingStyle = {
    fontSize: '1.6rem',
    color: '#f1c40f', 
    marginTop: '15px',
    fontWeight: '700',
};

/**
 * Componente que renderiza una tarjeta de Película o Serie.
 * @param {object} props.item - El objeto de datos del ítem.
 */
const ListaItems = ({ item }) => {
  return (
    <div style={cardStyle}>
      <div style={headerStyle}>
        {item.titulo}
      </div>
      <div style={detailsStyle}>
        **Tipo:** {item.tipo} | **Año:** {item.ano}
      </div>
      <div style={detailsStyle}>
        **Género:** *{item.genero}*
      </div>
      <div style={ratingStyle}>
        ⭐ {item.rating}
      </div>
    </div>
  );
};

export default ListaItems;
