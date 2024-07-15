import React from "react";
import { BrowserRouter as Router, Route, Routes } from "react-router-dom";
import UserForm from "./components/UserForm";
import Result from "./components/Result";

const App: React.FC = () => {
  return (
    <Router>
      <Routes>
        <Route path="/" element={<UserForm />} />
        <Route path="/result" element={<Result />} />
      </Routes>
    </Router>
  );
};

export default App;
