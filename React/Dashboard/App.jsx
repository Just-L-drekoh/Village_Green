import React from "react";
import { Route, Routes } from "react-router-dom";
import Layout from "./Layout.jsx";
import User from "./components/user/User.jsx";
import Order from "./components/order/Order.jsx";
import Turnover from "./components/turnover/Turnover.jsx";
import TurnoverSupplier from "./components/turnover/TurnoverSupplier.jsx";
import TopProductQuantity from "./components/topProduct/TopProductQuantity.jsx";
import TopProduct from "./components/topProduct/TopProduct.jsx";
const Dashboard = () => <h1>Admin Dashboard</h1>;

const App = () => {
  return (
    <div>
      <Routes>
        <Route path="/" element={<Layout />}>
          <Route path="admin/dashboard" element={<Dashboard />} />
          <Route path="orders" element={<Order />} />
          <Route path="users" element={<User />} />
          <Route path="turnover" element={<Turnover />} />
          <Route path="turnoverSupplier" element={<TurnoverSupplier />} />
          <Route path="topProductQuantity" element={<TopProductQuantity />} />
          <Route path="topProduct" element={<TopProduct />} />
        </Route>
      </Routes>
    </div>
  );
};

export default App;
