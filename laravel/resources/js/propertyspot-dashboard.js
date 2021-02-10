import React from 'react';
import ReactDOM from 'react-dom';
import App from 'propertyspot-dashboard/src/App';

const container = document.getElementById('propertyspot-dashboard');
ReactDOM.render(
    <App 
        title={container.getAttribute("title")}
        apiToken={container.getAttribute("apiToken")}
        apiUrl={container.getAttribute("apiUrl")}
        listingId={container.getAttribute("listingId")}
    />,
    container
);
