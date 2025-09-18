'use strict';
const {
  Model
} = require('sequelize');
module.exports = (sequelize, DataTypes) => {
  class Thesis extends Model {
    /**
     * Helper method for defining associations.
     * This method is not a part of Sequelize lifecycle.
     * The `models/index` file will call this method automatically.
     */
    static associate(models) {
      Thesis.belongsTo(models.User, { as: 'Student', foreignKey: 'studentId' });
      Thesis.belongsTo(models.User, { as: 'Lecturer', foreignKey: 'lecturerId' }); // Quan hệ mới
      Thesis.hasMany(models.ThesisVersion, { foreignKey: 'thesisId' });
    }
  }
  Thesis.init({
    title: DataTypes.STRING,
    description: DataTypes.TEXT,
    status: DataTypes.STRING,
    studentId: DataTypes.INTEGER,
    lecturerId: DataTypes.INTEGER
  }, {
    sequelize,
    modelName: 'Thesis',
  });
  return Thesis;
};