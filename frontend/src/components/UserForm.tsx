import React, { useState } from "react";
import { useNavigate } from "react-router-dom";
import axios from "axios";
import {
  Container,
  TextField,
  Button,
  Typography,
  List,
  ListItem,
  ListItemText,
  Box,
  Divider,
} from "@mui/material";

const UserForm: React.FC = () => {
  const [currentDate, setCurrentDate] = useState<string>("");
  const [ages, setAges] = useState<number[]>([]);
  const [ageInput, setAgeInput] = useState<string>("");
  const navigate = useNavigate();

  const handleAddAge = () => {
    if (ageInput) {
      setAges([...ages, parseInt(ageInput)]);
      setAgeInput("");
    }
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!currentDate || ages.length === 0) {
      alert("現在日時と参加者の年齢を入力してください。");
      return;
    }

    try {
      const response = await axios.post(
        "http://localhost:8000/api/optimal-schedule",
        {
          currentDate,
          ages,
        },
        {
          headers: {
            "Content-Type": "application/json",
          },
        }
      );
      navigate("/result", { state: { result: response.data } });
    } catch (error: any) {
      if (error.response && error.response.status === 404) {
        navigate("/result", { state: { result: null } });
      } else {
        console.error("Error fetching data", error);
      }
    }
  };

  return (
    <Container maxWidth="sm">
      <Typography variant="h4" component="h1" gutterBottom sx={{ mt: 4 }}>
        観覧スケジュール検索
      </Typography>
      <Box component="form" onSubmit={handleSubmit} noValidate sx={{ mt: 2 }}>
        <TextField
          label="現在日時"
          type="datetime-local"
          value={currentDate}
          onChange={(e) => setCurrentDate(e.target.value)}
          fullWidth
          required
          margin="normal"
          InputLabelProps={{
            shrink: true,
          }}
        />
        <TextField
          label="参加者の年齢"
          type="number"
          value={ageInput}
          onChange={(e) => setAgeInput(e.target.value)}
          fullWidth
          margin="normal"
        />
        <Button
          variant="contained"
          color="primary"
          onClick={handleAddAge}
          sx={{ mt: 1, mb: 2 }}
        >
          追加
        </Button>
        <Typography variant="h6" component="h2" gutterBottom>
          参加者一覧
        </Typography>
        <Divider />
        <List>
          {ages.map((age, index) => (
            <ListItem key={index}>
              <ListItemText primary={age} />
            </ListItem>
          ))}
        </List>
        <Button
          type="submit"
          variant="contained"
          color="secondary"
          fullWidth
          disabled={!currentDate || ages.length === 0}
        >
          最適なスケジュールを検索
        </Button>
      </Box>
    </Container>
  );
};

export default UserForm;
