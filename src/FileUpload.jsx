import React, { Component } from 'react';
import axios from 'axios';
import {UPLOAD_URL} from './constants';

class FileUpload extends Component {

    constructor(props) {
        super(props);
    
        this.state = {
          uploadStatus: false,
          messages: [],
        };
    
        this.handleUpload = this.handleUpload.bind(this);
      }

    // this could be optimized for multiple uploads at the same time or a drag-drop upload
    handleUpload(event) {
        event.preventDefault();

        let currentComponent = this;
        const data = new FormData();
        data.append('inputFile', this.inputFile.files[0]);
    
        axios.post(UPLOAD_URL, data)
          .then(resp => {
            const response = resp.data;
            const messages = response.messages;
              currentComponent.setState({
                    uploadStatus: true,
                    messages: messages
              });
          })
          .catch(function (error) {
            console.log(error);
          });
      }

    render() {
        const messages = this.state.messages.map((message, index) => {
            return (<p key={index}>{message}</p>)
        });

        return(
        <div className="col-sm m-3">
            <h2>Upload your file</h2>
            <form onSubmit={this.handleUpload}>
                <div className="form-group">
                    <input className="form-control" name="inputFile" id="inputFile" ref={(ref) => { this.inputFile = ref; }} type="file" />
                    <p className="form-text">Max file size 5MB</p>
                </div>
                <button className="form-control btn-primary">Upload</button>
            </form>
            <div>{messages}</div>
        </div>
        )
    }
}

export default FileUpload;