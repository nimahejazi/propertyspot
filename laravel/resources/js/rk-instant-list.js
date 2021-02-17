import React from 'react';
import ReactDOM from 'react-dom';
import App from 'rk-instant-list/src/App';
import axios from 'axios';

const container = document.getElementById('list-of-users');
const data = axios({
    method: 'get',
    url: '/api/all-users',
    params: {
        api_token: document.getElementById('api_token').value
    }
})
    .then(res => res.data)
    .then(data => {
        ReactDOM.render(
            <App
                data={data}
                actions={[
                    {
                        title: 'Show listings',
                        icon: 'pageview',
                        url: '/admin/users/{id}/listings'
                    },
                    {
                        title: 'Sign in as {email}',
                        icon: 'exit_to_app',
                        url: '/admin/users/{id}/login-as'
                    },
                ]}
                headers={[
                    { id: 'id', title: 'ID', numeric: true, sortable: true},
                    { id: 'email', title: 'Email', numeric: false, sortable: true},
                    { id: 'listings_count', title: 'Listings', numeric: true, sortable: true},
                ]}
            />,
            container
        );
    })
