import React, { Component } from 'react';
import axios from 'axios';
import {ALL_FILES_URL, DELETE_URL} from './constants';
import FileItem from './FileItem'

class ListFiles extends Component {
    constructor(props) {
        super(props);
        this.state = {
            requestFailed: false, 
            allFiles: [],
            messages: [],
        };
    }

    handleDelete = (fileName, e) => {
        const data = new FormData();
        data.append('fileName', fileName);
        let currentComponent = this;

        axios.post(DELETE_URL, data)
          .then(resp => {  
            const response = resp.data;
            const messages = response.messages;
            console.log('messages', JSON.stringify(messages));
            let allFiles = Object.assign([], currentComponent.state.allFiles);
            allFiles = allFiles.filter((obj) => (obj.name !== fileName));
            currentComponent.setState({
                allFiles: allFiles, 
                messages: messages
            });
          })
          .catch(function (error) {
            console.log(error);
          });   
    }

    componentDidMount() {
        let currentComponent = this;
        axios.get(ALL_FILES_URL)
            .then(resp => {
                const response = resp.data;
                const data = response.data;
                if(data) {
                    // initially there will be no documents to list
                    currentComponent.setState({
                        allFiles: data,
                    });
                }
                
            })
            .catch(function (error) {
                currentComponent.setState({requestFailed: true});
                console.log(error);
            });
    }

    render () {
        if(this.state.requestFailed && this.state.requestFailed === false)  
            return (<div className="container">Request Failed...</div>)

        const messages = this.state.messages;

        return (
            <div className="col-sm m-3">
                <h2> Your Files </h2>
                <ul className="list-group list-group-flush">
                    {
                        this.state.allFiles.map((item) => {
                            return (<FileItem url={item.url} key={item.name} deleteEvent={this.handleDelete.bind(this, item.name)}>{item.name}</FileItem>)
                        })
                    }
                </ul>
                {messages}
            </div>
        )
    }
}

export default ListFiles;