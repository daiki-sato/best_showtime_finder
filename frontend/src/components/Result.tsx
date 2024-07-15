import React from "react";
import { useLocation, useNavigate } from "react-router-dom";
import { Container, Typography, Button, Box } from "@mui/material";

const Result: React.FC = () => {
  const location = useLocation();
  const navigate = useNavigate();
  const result = location.state?.result;

  if (!result) {
    return (
      <Container maxWidth="sm" sx={{ mt: 4 }}>
        <Typography variant="h5" component="h1" gutterBottom sx={{ mb: 4 }}>
          結果が見つかりませんでした。
        </Typography>
        <Button
          variant="contained"
          color="primary"
          onClick={() => navigate("/")}
        >
          戻る
        </Button>
      </Container>
    );
  }

  return (
    <Container maxWidth="sm" sx={{ mt: 4 }}>
      <Typography variant="h4" component="h1" gutterBottom>
        最適なスケジュール
      </Typography>
      <Box sx={{ mt: 2 }}>
        <Typography variant="body1">
          日時: {result.date} {result.time}
        </Typography>
        <Typography variant="body1">料金: ¥{result.price}</Typography>
      </Box>
      <Button
        variant="contained"
        color="primary"
        onClick={() => navigate("/")}
        sx={{ mt: 2 }}
      >
        戻る
      </Button>
    </Container>
  );
};

export default Result;
