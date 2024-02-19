import React, { useState } from 'react'
import { FileUploader } from 'react-drag-drop-files'
import { DragAndDropDesing } from '@/components/DragAndDropDesing.jsx'

const fileTypes = ['CSV']

function DragDrop () {
  const [file, setFile] = useState(null)
  const handleChange = (file) => {
    setFile(file)
  }
  return (
    <FileUploader handleChange={handleChange} name='file' types={fileTypes}>
      {/* <div><p>this is inside drop area</p></div>  Hacer diseño del drop area */}
      <DragAndDropDesing />
    </FileUploader>
  )
}

export default DragDrop
