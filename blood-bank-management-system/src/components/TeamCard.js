import React from "react";

const TeamCard = ({ name, role, image }) => {
  return (
    <div className="bg-white p-6 rounded-xl shadow-md hover:shadow-xl transition duration-300 transform hover:-translate-y-2 flex flex-col items-center text-center">
      <img
        src={image}
        alt={name}
        className="w-24 h-24 rounded-full object-cover border-4 border-purple-200 mb-4"
      />
      <h3 className="font-semibold text-lg text-gray-800">{name}</h3>
      <p className="text-sm text-gray-500">{role}</p>
    </div>
  );
};

export default TeamCard;
