import React from 'react';

const FileItem = (props) => {
    return  (
        <li className="list-group-item">
            <a href={props.url} target="_blank" rel="noopener noreferrer"><span>{props.children}</span> </a>     
            <button className="btn btn-secondary btn-sm" onClick={props.deleteEvent}>Delete</button>
        </li>
    )

}

export default FileItem;