import React from "react";

const UserList = ({ users }) => {
  return (
    <ul className="space-y-4">
      {users.map((user) => (
        <li
          key={user.firstName}
          className="flex items-center p-4 border border-gray-200 rounded-lg shadow-sm hover:shadow-md hover:scale-[1.02] transition-transform duration-200"
        >
          <div className="flex-grow">
            <h3 className="text-lg font-semibold text-gray-800">
              {user.firstName} {user.lastName}
            </h3>
          </div>
          <div className="text-sm text-gray-500">
            <p className="font-medium text-indigo-600">{user.email}</p>
            <p>{user.phone}</p>
          </div>
          
        </li>
      ))}
    </ul>
  );
};

export default UserList;
