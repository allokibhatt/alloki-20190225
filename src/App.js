import 'bootstrap/dist/css/bootstrap.css';
import React, { Component } from 'react';
import FileUpload from './FileUpload';
import ListFiles from './ListFiles';
// import logo from './logo.svg';

import './App.css';


class App extends Component {
  render() {
    return (
      <div className="App container">
        <h1> Your document manager</h1>
        <div className="row">
          <FileUpload />
          <ListFiles />
        </div>
      </div>
    );
  }
}

export default App;
